<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:48
 */

namespace Product\Controller\Factory;

use Product\Service\VersionService;
use Interop\Container\ContainerInterface;
use Product\Controller\VersionController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VersionControllerFactory
 * @package Product\Controller\Factory
 */
class VersionControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|VersionController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $versionService = $container->get(VersionService::class);
        return new VersionController($versionService);
    }
}