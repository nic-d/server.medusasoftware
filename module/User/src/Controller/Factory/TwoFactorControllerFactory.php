<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:42
 */

namespace User\Controller\Factory;

use User\Service\TwoFactorService;
use User\Controller\TwoFactorController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TwoFactorControllerFactory
 * @package User\Controller\Factory
 */
class TwoFactorControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|TwoFactorController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $twoFactorService = $container->get(TwoFactorService::class);
        return new TwoFactorController($twoFactorService);
    }
}