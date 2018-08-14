<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:33
 */

namespace Product\Service\Factory;

use Product\Service\ProductService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProductServiceFactory
 * @package Product\Service\Factory
 */
class ProductServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|ProductService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $envatoService = $container->get('EnvatoApiService');
        $formManager = $container->get('FormElementManager');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ProductService($entityManager, $formManager, $envatoService);
    }
}