<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 22:10
 */

namespace License\Service\Factory;

use License\Service\LicenseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LicenseServiceFactory
 * @package License\Service\Factory
 */
class LicenseServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LicenseService|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $eventManager = $container->get('EventManager');
        $envatoService = $container->get('EnvatoApiService');
        $formManager = $container->get('FormElementManager');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new LicenseService($entityManager, $formManager, $envatoService, $eventManager);
    }
}