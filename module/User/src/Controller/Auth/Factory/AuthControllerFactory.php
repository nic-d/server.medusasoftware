<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 08:01
 */

namespace User\Controller\Auth\Factory;

use User\Service\UserService;
use Email\Service\EmailService;
use User\Service\Auth\AuthService;
use User\Service\TwoFactorService;
use User\Controller\Auth\AuthController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthControllerFactory
 * @package User\Auth\Controller\Factory
 */
class AuthControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|AuthController
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService   = $container->get(AuthService::class);
        $userService   = $container->get(UserService::class);
        $twoFactorService = $container->get(TwoFactorService::class);

        return new AuthController($authService, $userService, $twoFactorService);
    }
}