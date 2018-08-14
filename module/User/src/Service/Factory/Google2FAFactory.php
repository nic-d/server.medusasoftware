<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:03
 */

namespace User\Service\Factory;

use PragmaRX\Google2FA\Google2FA;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class Google2FAFactory
 * @package User\Service\Factory
 */
class Google2FAFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|Google2FA
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Google2FA $google2fa */
        $google2fa = new Google2FA();
        return $google2fa;
    }
}