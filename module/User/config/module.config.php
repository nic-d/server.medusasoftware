<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 06:15
 */

namespace User;

use User\Entity;
use User\Service;
use User\Controller;
use Zend\Router\Http\Literal;
use PragmaRX\Google2FA\Google2FA;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'user.account' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/user/account',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'account',
                    ],
                ],

                'may_terminate' => true,
                'child_routes' => [
                    '2fa' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/2fa',
                            'defaults' => [
                                'controller' => Controller\TwoFactorController::class,
                                'action'     => 'updateTwoFactor',
                            ],
                        ],
                    ],

                    '2fa.disable' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/2fa/disable',
                            'defaults' => [
                                'controller' => Controller\TwoFactorController::class,
                                'action'     => 'disableTwoFactor',
                            ],
                        ],
                    ],

                    '2fa.generate' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/2fa/generate',
                            'defaults' => [
                                'controller' => Controller\TwoFactorController::class,
                                'action'     => 'generateBackupCodes',
                            ],
                        ],
                    ],
                ],
            ],

            'user.login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\Auth\AuthController::class,
                        'action'     => 'login',
                    ],
                ],

                'may_terminate' => true,
                'child_routes' => [
                    '2fa' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/2fa',
                            'defaults' => [
                                'controller' => Controller\Auth\AuthController::class,
                                'action'     => 'twoFactorLogin',
                            ],
                        ],
                    ],
                ],
            ],

            'user.logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/logout',
                    'defaults' => [
                        'controller' => Controller\Auth\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\Auth\AuthController::class => Controller\Auth\Factory\AuthControllerFactory::class,
            Controller\TwoFactorController::class => Controller\Factory\TwoFactorControllerFactory::class,
        ],
    ],

    'access_filter' => [
        'resources' => [
            Controller\UserController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['account'],
                ],
            ],

            Controller\Auth\AuthController::class => [
                [
                    'roles'   => ['Guest'],
                    'actions' => ['login', 'twoFactorLogin', 'logout'],
                ],
            ],

            Controller\TwoFactorController::class => [
                [
                    'roles'   => ['User'],
                    'actions' => ['updateTwoFactor', 'disableTwoFactor', 'generateBackupCodes'],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Google2FA::class => Service\Factory\Google2FAFactory::class,
            Entity\Subscriber\UserSubscriber::class => InvokableFactory::class,
            Service\AclService::class => Service\Factory\AclServiceFactory::class,
            Service\UserService::class => Service\Factory\UserServiceFactory::class,
            Service\TwoFactorService::class => Service\Factory\TwoFactorServiceFactory::class,
            Service\Auth\AuthService::class => Service\Auth\Factory\AuthServiceFactory::class,
            Service\Auth\AuthAdapterService::class => Service\Auth\Factory\AuthAdapterFactory::class,
            AuthenticationService::class => Service\Auth\Factory\AuthenticationServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'user_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'orm_default' => [
                'drivers' => [
                    'User\Entity' => 'user_driver',
                ],
            ],
        ],
    ],
];