<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:40
 */

use Install\Service;
use Install\Controller;
use Zend\Router\Http\Literal;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'install.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/installs',
                    'defaults' => [
                        'controller' => Controller\InstallController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'install.api' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/api/installs',
                    'defaults' => [
                        'controller' => Controller\InstallApiController::class,
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\InstallController::class => Controller\Factory\InstallControllerFactory::class,
            Controller\InstallApiController::class => Controller\Factory\InstallApiControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\InstallController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['index'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\InstallService::class => Service\Factory\InstallServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'install_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'Install\Entity' => 'install_driver',
                ],
            ],
        ],
    ],
];