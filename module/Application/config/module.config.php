<?php
declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Application\Controller\ApiController;
use Application\Controller\ItemController;
use Application\Controller\AboutController;
use Doctrine\ORM\EntityManager;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // added api caller
            'api-rates' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api/rates',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'latest',
                    ],
                ],
            ],
            'item' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/item[/:currency]',
                    'defaults' => [
                        'controller' => Controller\ItemController::class,
                        'action'     => 'view',
                    ],
                    'constraints' => [
                        'currency' => '[^/]+',
                    ],
                ],
            ],
            'about' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/about[/:page]',
                    'defaults' => [
                        'controller' => Controller\AboutController::class,
                        'action'     => 'about',
                        'page'       => 'me', // default page
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            AboutController::class => InvokableFactory::class,
            ApiController::class => function($container) {
                $entityManager = $container->get(EntityManager::class);
                return new ApiController($entityManager);
            },
            ItemController::class => function($container) {
                $entityManager = $container->get(EntityManager::class);
                return new ItemController($entityManager);
            },
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        // added strategy to receive json from the api 
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    // added helper to call translate globally
    'view_helpers' => [
    'factories' => [
        \Application\Helper\Translate::class => function ($container) {
            $primary_language = 'en';
        
            if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                preg_match('/^([a-z]{2})/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
                if (!empty($matches[1])) {
                    $primary_language = strtolower($matches[1]);
                }
            }
            $langsArray = ['en', 'pt'];
            if (!in_array($primary_language, $langsArray)) {
                $primary_language = 'en';
            }
            
            $basePath = dirname(__DIR__, 2);
            $translations = [];

            if($basePath === '/var/www/html'){
                $translationPath = $basePath . "/data/translations/";
            } else {
                $translationPath = __DIR__ . "/../../../data/translations/";
            }

            $translationFile = $translationPath . "{$primary_language}.json";
            
            if (!file_exists($translationFile)) {
                $translationFile = $translationPath .  "en.json";
            }
        
            if (file_exists($translationFile)) {
                $content = file_get_contents($translationFile);
                $translations = json_decode($content, true) ?: [];
            }

            return new \Application\Helper\Translate($translations);
        },
    ],
    'aliases' => [
        't' => \Application\Helper\Translate::class,
    ],
],

];
