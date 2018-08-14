<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:49
 */

namespace Install\Controller\Factory;

use Install\Service\InstallService;
use Install\Controller\InstallController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class InstallControllerFactory
 * @package Install\Controller\Factory
 */
class InstallControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InstallController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $installService = $container->get(InstallService::class);
        return new InstallController($installService);
    }
}