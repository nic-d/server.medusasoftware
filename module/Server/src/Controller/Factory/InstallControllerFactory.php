<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:56
 */

namespace Server\Controller\Factory;

use Server\Service\InstallService;
use Server\Controller\InstallController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallControllerFactory
 * @package Server\Controller\Factory
 */
class InstallControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|InstallController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $installService = $container->get(InstallService::class);
        return new InstallController($installService);
    }
}