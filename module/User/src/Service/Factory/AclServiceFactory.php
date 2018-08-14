<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 30/03/2018
 * Time: 10:12
 */

namespace User\Service\Factory;

use User\Service\AclService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AclServiceFactory
 * @package User\Service\Factory
 */
class AclServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|AclService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $accessFilter */
        $accessFilter = $container->get('Config')['access_filter'];
        return new AclService($accessFilter);
    }
}