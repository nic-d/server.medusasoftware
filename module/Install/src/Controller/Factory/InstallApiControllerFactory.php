<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:49
 */

namespace Install\Controller\Factory;

use Install\Service\InstallService;
use Interop\Container\ContainerInterface;
use Install\Controller\InstallApiController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallApiControllerFactory
 * @package Install\Controller\Factory
 */
class InstallApiControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InstallApiController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $installService = $container->get(InstallService::class);
        return new InstallApiController($installService);
    }
}