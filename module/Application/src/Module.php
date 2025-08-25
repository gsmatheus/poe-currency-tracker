<?php

declare(strict_types=1);

namespace Application;
use Doctrine\ORM\EntityManager;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ApiController::class => function($container) {
                    return new Controller\ApiController(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

}
