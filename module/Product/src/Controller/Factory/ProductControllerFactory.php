<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:17
 */

namespace Product\Controller\Factory;

use Product\Service\ProductService;
use Interop\Container\ContainerInterface;
use Product\Controller\ProductController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProductControllerFactory
 * @package Product\Controller\Factory
 */
class ProductControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|ProductController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $productService = $container->get(ProductService::class);
        return new ProductController($productService);
    }
}