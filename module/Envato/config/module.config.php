<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/08/2018
 * Time: 21:32
 */

use Envato\Service;

return [
    'service_manager' => [
        'factories' => [
            Service\EnvatoService::class => Service\Factory\EnvatoServiceFactory::class,
        ],

        'aliases' => [
            'EnvatoApiService' => Service\EnvatoService::class,
        ],
    ],
];