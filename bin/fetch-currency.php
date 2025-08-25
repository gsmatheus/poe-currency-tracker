#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Application\Entity\CurrencyRate;
use Doctrine\ORM\EntityManager;

$container = require __DIR__ . '/../config/container.php';
$em = $container->get(EntityManager::class);

$cacheDir = __DIR__ . '/../data/cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

$league = $argv[1] ?? null;

if (!$league) {
  fwrite(STDERR, "Usage: php update-currency.php <league>\n");
  exit(1);
}

$apiUrl = "https://poe.ninja/api/data/currencyoverview?league=" . urlencode($league) . "&type=Currency";

// some error handling 
$context = stream_context_create([
  'http' => [
    'timeout' => 10, // seconds
    'ignore_errors' => true
  ]
]);

$response = @file_get_contents($apiUrl, false, $context);

if ($response === false) {
  fwrite(STDERR, "Error: Failed to fetch data from poe.ninja.\n");
  exit(1);
}

// Check HTTP status code
$httpCode = 200;
if (isset($http_response_header[0])) {
  preg_match('{HTTP/\S*\s(\d{3})}', $http_response_header[0], $match);
  $httpCode = (int)($match[1] ?? 0);
}

if ($httpCode !== 200) {
  fwrite(STDERR, "Error: API returned status code $httpCode\n");
  exit(1);
}

// Decode JSON safely
$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
  fwrite(STDERR, "Error: Failed to parse JSON (" . json_last_error_msg() . ")\n");
  exit(1);
}

if (empty($data['currencyDetails']) || empty($data['lines'])) {
  fwrite(STDERR, "Error: Missing expected fields in API response.\n");
  exit(1);
}

// a lookup table to prep currencyIcons
$detailsByName = [];
foreach ($data['currencyDetails'] as $detail) {
  $detailsByName[strtolower($detail['name'])] = $detail;
}

// Insert records
foreach ($data['lines'] as $line) {
  $detail = $detailsByName[strtolower($line['currencyTypeName'])] ?? null;

  $chaosValue = $line['chaosEquivalent'] ?? null;
  $listingCount = $line['receive']['listing_count'] ?? 0;
  $icon = $detail['icon'] ?? null;

  // Skip invalid entries
  // i only skip here because i dont intend to use these values, but it could be good to save then if necessity strikes
  // (eg: see ones that are missing for additional data)
  if (!$icon || !$chaosValue || $listingCount <= 0) {
    continue;
  }

  $currency = new CurrencyRate();
  $currency->setCurrency($line['currencyTypeName']);
  $currency->setValue($line['chaosEquivalent']);
  $currency->setFetchedAt(new \DateTime());
  $currency->setIcon($detail['icon'] ?? null);
  $currency->setLeague($league);
  $count = $line['receive']['listing_count'];
  
  $currency->setListingCount($count);

  $em->persist($currency);
}

$em->flush();
echo "Currency rates updated successfully.\n";
