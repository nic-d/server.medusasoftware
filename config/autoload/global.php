<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Storage\SessionArrayStorage;

return [
    'session_config' => [
        'cookie_lifetime' => 60*60*1,
        'gc_maxlifetime'  => 60*60*24*30,
    ],

    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],

    'session_storage' => [
        'type' => SessionArrayStorage::class,
    ],

    'view_manager' => [
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',

        'template_map' => [
            'layout/layout'           => getcwd() . '/module/Application/src/view/layout/layout.twig',
            'layout/ajax'             => getcwd() . '/module/Application/src/view/layout/ajax.twig',
            'layout/admin'            => getcwd() . '/module/Application/src/view/layout/admin.twig',
            'error/404'               => getcwd() . '/module/Application/src/view/error/404.twig',
            'error/index'             => getcwd() . '/module/Application/src/view/error/index.twig',
        ],

        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul class="list-unstyled"><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>',
        ],
    ],

    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../lang',
                'pattern' => '%s.php',
            ],
        ],
    ],

    'log' => [
        'App' => [
            'exceptionhandler' => true,
            'errorhandler' => true,
            'fatal_error_shutdownfunction' => true,

            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => getcwd() . '/data/tmp/' . date('Y-m-d') . '.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'zend_twig' => [
        'extensions' => [],
    ],

    'envato_api' => [
        'client_id'     => getenv('ENVATO_CLIENT_ID'),
        'client_secret' => getenv('ENVATO_SECRET_KEY'),
        'redirect_uri'  => getenv('APP_URL') . 'envato/oauth/callback',
        'app_name'      => getenv('ENVATO_APP_NAME'),
    ],

    'caches' => [
        'fscache' => [
            'adapter' => [
                'name'     =>'filesystem',
                'options'  => [
                    'cache_dir' => 'data/cache',
                ],
            ],
        ],
    ],

    'bsb_flysystem' => [
        'adapters' => [
            'local_files' => [
                'type' => 'local',
                'options' => [
                    'root' => 'public/upload/',
                ],
            ],
        ],

        'filesystems' => [
            'files' => [
                'adapter' => 'local_files',
                'cache' => false,
                'eventable' => false,
                'plugins' => [],
            ],
        ],

        'filesystem_opts' => [
            'baseUrl' => getenv('APP_URL') . 'upload/',
        ],
    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                    'host'     => getenv('DB_VM_HOST'),
                    'port'     => getenv('DB_PORT'),
                    'user'     => getenv('DB_USERNAME'),
                    'password' => getenv('DB_PASSWORD'),
                    'dbname'   => getenv('DB_NAME'),
                ],
            ],
        ],

        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'Gedmo\Sluggable\SluggableListener',
                ],
            ],
        ],
    ],
];