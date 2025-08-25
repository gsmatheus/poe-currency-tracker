#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Application\Entity\Item; // create an entity for your currency/items
use Doctrine\ORM\EntityManager;

$container = require 'config/container.php';
$em = $container->get(EntityManager::class);

$jsonPath = __DIR__ . '/items.json';
$itemsRaw = json_decode(file_get_contents($jsonPath), true);

if (!$itemsRaw) {
    fwrite(STDERR, "Failed to load or parse items.json\n");
    exit(1);
}

foreach ($itemsRaw as $key => $itemData) {
    // Filter only stackable currency items
    if (($itemData['item_class'] ?? '') !== 'StackableCurrency') {
        continue;
    }

    $tags = $itemData['tags'] ?? [];
    if (!in_array('currency', $tags, true)) {
        continue;
    }

    $name = $itemData['name'] ?? null;
    $description = $itemData['properties']['description'] ?? '';
    $directions = $itemData['properties']['directions'] ?? '';
    $stackSize = $itemData['properties']['stack_size'] ?? 1;

    if (!$name) {
        continue; // skip invalid entries
    }

    // Check if it exists in DB
    $existing = $em->getRepository(Item::class)->findOneBy(['name' => $name]);
    if ($existing) {
        $item = $existing;
    } else {
        $item = new Item();
        $item->setName($name);
    }

    $item->setDescription($description);
    $item->setDirections($directions);
    $item->setStackSize($stackSize);

    $em->persist($item);
}

// Flush all changes
$em->flush();

echo "Items imported successfully!\n";
