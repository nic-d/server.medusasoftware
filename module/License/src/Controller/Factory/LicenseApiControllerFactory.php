<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 15:19
 */

namespace License\Controller\Factory;

use License\Service\LicenseService;
use Interop\Container\ContainerInterface;
use License\Controller\LicenseApiController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LicenseApiControllerFactory
 * @package License\Controller\Factory
 */
class LicenseApiControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LicenseApiController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $licenseService = $container->get(LicenseService::class);
        return new LicenseApiController($licenseService);
    }
}