<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:49
 */

use Activity\Event;
use Activity\Service;
use Activity\Controller;
use Zend\Router\Http\Literal;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'activity.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/activity',
                    'defaults' => [
                        'controller' => Controller\ActivityController::class,
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
                                'action'     => 'search',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\ActivityController::class => Controller\Factory\ActivityControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\ActivityController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['index', 'search'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Event\RegisterEvents::class => Event\Factory\RegisterEventsFactory::class,
            Service\ActivityService::class => Service\Factory\ActivityServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'activity_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'Activity\Entity' => 'activity_driver',
                ],
            ],
        ],
    ],
];