<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:13
 */

use Generate\Form;
use Generate\Service;
use Generate\Controller;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'generate.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/generate',
                    'defaults' => [
                        'controller' => Controller\GenerateController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\GenerateController::class => Controller\Factory\GenerateControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\GenerateController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['index'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\GenerateService::class => Service\Factory\GenerateServiceFactory::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\GenerateForm::class => Form\Factory\GenerateFormFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];