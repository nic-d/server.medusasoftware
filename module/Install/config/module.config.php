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
use Zend\Router\Http\Segment;
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

                'may_terminate' => true,
                'child_routes'  => [
                    'search' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/search',
                            'defaults' => [
                                'action' => 'search',
                            ],
                        ],
                    ],

                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:hash]/view',
                            'constraints' => [
                                'hash' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],
                ],
            ],

            'install.api' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/api/install',
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
                    'actions' => ['index', 'search', 'view'],
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