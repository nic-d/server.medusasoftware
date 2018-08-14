<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 08:11
 */

namespace User\Service\Auth\Factory;

use Zend\Session\SessionManager;
use User\Service\Auth\AuthAdapterService;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * Class AuthenticationServiceFactory
 * @package User\Auth\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|AuthenticationService
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager     = $container->get(SessionManager::class);
        $authStorage        = new SessionStorage('Zend_Auth', 'session', $sessionManager);
        $authAdapterService = $container->get(AuthAdapterService::class);

        return new AuthenticationService($authStorage, $authAdapterService);
    }
}