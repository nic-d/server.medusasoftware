<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 22:09
 */

namespace License\Controller\Factory;

use License\Service\LicenseService;
use Interop\Container\ContainerInterface;
use License\Controller\LicenseController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LicenseControllerFactory
 * @package License\Controller\Factory
 */
class LicenseControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LicenseController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $licenseService = $container->get(LicenseService::class);
        return new LicenseController($licenseService);
    }
}