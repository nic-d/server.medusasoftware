<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:59
 */

namespace Server\Service\Factory;

use Server\Service\InstallService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallServiceFactory
 * @package Server\Service\Factory
 */
class InstallServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|InstallService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new InstallService();
    }
}