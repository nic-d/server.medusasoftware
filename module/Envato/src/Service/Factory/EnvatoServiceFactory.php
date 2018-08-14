<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/08/2018
 * Time: 21:33
 */

namespace Envato\Service\Factory;

use Envato\Service\EnvatoService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EnvatoServiceFactory
 * @package Envato\Service\Factory
 */
class EnvatoServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return EnvatoService|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')['envato_api'];
        return new EnvatoService($config['client_id'], $config['client_secret']);
    }
}