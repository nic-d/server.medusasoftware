<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:21
 */

namespace User\Service\Factory;

use PragmaRX\Google2FA\Google2FA;
use User\Service\TwoFactorService;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TwoFactorServiceFactory
 * @package User\Service\Factory
 */
class TwoFactorServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|TwoFactorService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $google2FAService = $container->get(Google2FA::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authenticationService = $container->get(AuthenticationService::class);

        return new TwoFactorService($entityManager, $google2FAService, $authenticationService);
    }
}