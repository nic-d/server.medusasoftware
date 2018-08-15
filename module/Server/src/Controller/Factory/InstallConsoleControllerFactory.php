<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 15:46
 */

namespace Server\Controller\Factory;

use Server\Service\InstallService;
use Interop\Container\ContainerInterface;
use Server\Controller\InstallConsoleController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallConsoleControllerFactory
 * @package Server\Controller\Factory
 */
class InstallConsoleControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|InstallConsoleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $installService = $container->get(InstallService::class);
        return new InstallConsoleController($installService);
    }
}