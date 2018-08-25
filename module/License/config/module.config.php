<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 11/08/2018
 * Time: 23:33
 */

use License\Form;
use License\Service;
use License\Controller;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'license.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/licenses',
                    'defaults' => [
                        'controller' => Controller\LicenseController::class,
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

                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ],
                        ],
                    ],

                    'verify' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/verify',
                            'defaults' => [
                                'action' => 'verify',
                            ],
                        ],
                    ],

                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:code]/view',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],

                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:code]/edit',
                            'constraints' => [
                                'code' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],

                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:code]/delete',
                            'constraints' => [
                                'hash' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                ],
            ],

            'license.api' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/api/license',
                    'defaults' => [
                        'controller' => Controller\LicenseApiController::class,
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\LicenseController::class => Controller\Factory\LicenseControllerFactory::class,
            Controller\LicenseApiController::class => Controller\Factory\LicenseApiControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\LicenseController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['index', 'search', 'verify', 'add', 'view', 'edit', 'delete'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\LicenseService::class => Service\Factory\LicenseServiceFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\LicenseAddForm::class => InvokableFactory::class,
            Form\LicenseEditForm::class => InvokableFactory::class,
            Form\LicenseDeleteForm::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'license_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'License\Entity' => 'license_driver',
                ],
            ],
        ],
    ],
];