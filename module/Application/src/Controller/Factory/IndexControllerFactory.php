<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 22:45
 */

namespace Application\Controller\Factory;

use Install\Service\InstallService;
use License\Service\LicenseService;
use Product\Service\ProductService;
use Interop\Container\ContainerInterface;
use Application\Controller\IndexController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 * @package Application\Controller\Factory
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IndexController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $licenseService = $container->get(LicenseService::class);
        $installService = $container->get(InstallService::class);
        $productService = $container->get(ProductService::class);

        return new IndexController($installService, $licenseService, $productService);
    }
}