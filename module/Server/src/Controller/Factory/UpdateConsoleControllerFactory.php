<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 15:47
 */

namespace Server\Controller\Factory;

use Interop\Container\ContainerInterface;
use Server\Controller\UpdateConsoleController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UpdateConsoleControllerFactory
 * @package Server\Controller\Factory
 */
class UpdateConsoleControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|UpdateConsoleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UpdateConsoleController();
    }
}