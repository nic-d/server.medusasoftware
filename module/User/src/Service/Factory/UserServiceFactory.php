<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 08:00
 */

namespace User\Service\Factory;

use User\Service\UserService;
use User\Service\TwoFactorService;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserServiceFactory
 * @package User\Service\Factory
 */
class UserServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|UserService
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authenticationService = $container->get(AuthenticationService::class);
        $twoFactorService = $container->get(TwoFactorService::class);

        return new UserService($entityManager, $authenticationService, $twoFactorService);
    }
}