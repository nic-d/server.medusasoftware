<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Application\Service\Twig\MarkdownExtension;
use Zend\ServiceManager\Factory\InvokableFactory;
use Application\View\Helper\Factory\UrlHelperFactory;
use Application\Service\Twig\Factory\MarkdownExtensionFactory;

return [
    'router' => [
        'routes' => [
            'application.index' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'application.home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\IndexController::class => [
                [
                    'roles'   => ['Guest'],
                    'actions' => ['index'],
                ],
                [
                    'roles'   => ['User'],
                    'actions' => ['home'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            MarkdownExtension::class => MarkdownExtensionFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'urlHelper' => UrlHelperFactory::class,
        ],
    ],
];