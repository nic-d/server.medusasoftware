<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 16:32
 */

namespace Product\Controller\Factory;

use Product\Service\VersionApiService;
use Interop\Container\ContainerInterface;
use Product\Controller\VersionApiController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VersionApiControllerFactory
 * @package Product\Controller\Factory
 */
class VersionApiControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|VersionApiController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $versionApiService = $container->get(VersionApiService::class);
        return new VersionApiController($versionApiService);
    }
}