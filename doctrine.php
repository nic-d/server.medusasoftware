<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

require('vendor/autoload.php');

/** @var \Dotenv\Dotenv $dotenv */
$dotenv = new \Dotenv\Dotenv(getcwd());
$dotenv->load();

$appConfig = require('config/application.config.php');
if (file_exists('config/development.config.php')) {
    $appConfig = ArrayUtils::merge($appConfig, require('config/development.config.php'));
}

$application = Application::init($appConfig);

/* @var $cli \Symfony\Component\Console\Application */
$cli = $application->getServiceManager()->get('doctrine.cli');
exit($cli->run());