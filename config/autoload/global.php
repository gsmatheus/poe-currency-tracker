<?php
// defining orm conn
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDO\SQLite\Driver::class,
                'params' => [
                    'path' => realpath(__DIR__ . '/../../data/poe_tracker.sqlite'),
                ],
            ],
        ],
        'driver' => [
            // register driver to use namespace Application\Entity
            'application_entity' => [
                'class' => Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../../module/Application/src/Entity'], // <-- path to entities 
            ],

            'orm_default' => [
                'drivers' => [
                    'Application\Entity' => 'application_entity',
                ],
            ],
        ],
    ],
];

?>