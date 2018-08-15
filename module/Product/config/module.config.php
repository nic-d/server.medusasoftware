<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:12
 */

use Product\Form;
use Product\Service;
use Product\Controller;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'product.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/products',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                ],

                'may_terminate' => true,
                'child_routes'  => [
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'action' => 'add',
                            ],
                        ],
                    ],

                    'view' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:hash]/view',
                            'constraints' => [
                                'hash' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'view',
                            ],
                        ],
                    ],

                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:hash]/edit',
                            'constraints' => [
                                'hash' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'edit',
                            ],
                        ],
                    ],

                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/[:hash]/delete',
                            'constraints' => [
                                'hash' => '[a-zA-Z0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\ProductController::class => Controller\Factory\ProductControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\ProductController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['index', 'add', 'view', 'edit', 'delete'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\ProductService::class => Service\Factory\ProductServiceFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\ProductAddForm::class => InvokableFactory::class,
            Form\ProductEditForm::class => InvokableFactory::class,
            Form\ProductDeleteForm::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'product_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'Product\Entity' => 'product_driver',
                ],
            ],
        ],
    ],
];