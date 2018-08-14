<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 08:00
 */

namespace User\Service\Auth\Factory;

use Zend\Session\SessionManager;
use User\Service\Auth\AuthService;
use User\Service\TwoFactorService;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthServiceFactory
 * @package User\Auth\Service\Factory
 */
class AuthServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|AuthService
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get(AuthenticationService::class);
        $sessionManager = $container->get(SessionManager::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $twoFactorService = $container->get(TwoFactorService::class);

        return new  AuthService($authenticationService, $sessionManager, $entityManager, $twoFactorService);
    }
}