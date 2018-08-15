<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 13:57
 */

namespace Server\Controller\Factory;

use Server\Controller\UpdateController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UpdateControllerFactory
 * @package Server\Controller\Factory
 */
class UpdateControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|UpdateController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UpdateController();
    }
}