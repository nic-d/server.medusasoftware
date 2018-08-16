<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 16:56
 */

namespace Product\Service\Factory;

use License\Service\LicenseService;
use Product\Service\ProductService;
use Product\Service\VersionService;
use Product\Service\VersionApiService;
use Product\Filter\DownloadInputFilter;
use Interop\Container\ContainerInterface;
use BsbFlysystem\Service\FilesystemManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VersionApiServiceFactory
 * @package Product\Service\Factory
 */
class VersionApiServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|VersionApiService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $versionService = $container->get(VersionService::class);
        $productService = $container->get(ProductService::class);
        $licenseService = $container->get(LicenseService::class);
        $filesystem = $container->get(FilesystemManager::class)->get('files');
        $downloadInputFilter = $container->get('InputFilterManager')->get(DownloadInputFilter::class);

        return new VersionApiService($productService, $versionService, $licenseService, $downloadInputFilter, $filesystem);
    }
}