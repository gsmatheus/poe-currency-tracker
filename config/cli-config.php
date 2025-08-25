<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require __DIR__ . '/../vendor/autoload.php';

// Boot Laminas container
$container = require __DIR__ . '/container.php';
$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

// Return helper set for CLI
return ConsoleRunner::createHelperSet($entityManager);
