<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:52
 */

use Server\Form;
use Server\Service;
use Server\Controller;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'server.install' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/server/install',
                    'defaults' => [
                        'controller' => Controller\InstallController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'server.update' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/server/update',
                    'defaults' => [
                        'controller' => Controller\UpdateController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\UpdateController::class => Controller\Factory\UpdateControllerFactory::class,
            Controller\InstallController::class => Controller\Factory\InstallControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\UpdateController::class => [
                [
                    'roles'   => ['Guest'],
                    'actions' => ['index'],
                ],
            ],

            Controller\InstallController::class => [
                [
                    'roles'   => ['Guest'],
                    'actions' => ['index'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\UpdateService::class => Service\Factory\UpdateServiceFactory::class,
            Service\InstallService::class => Service\Factory\InstallServiceFactory::class,
            Service\AbstractService::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\InstallForm::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];