<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 08:01
 */

namespace User\Controller\Factory;

use User\Service\UserService;
use User\Service\TwoFactorService;
use User\Controller\UserController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserControllerFactory
 * @package User\Controller\Factory
 */
class UserControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|UserController
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userService = $container->get(UserService::class);
        $twoFactorService = $container->get(TwoFactorService::class);

        // Instantiate the controller and inject dependencies
        return new UserController($userService, $twoFactorService);
    }
}