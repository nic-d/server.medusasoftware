<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:15
 */

namespace Generate\Controller\Factory;

use Generate\Service\GenerateService;
use Interop\Container\ContainerInterface;
use Generate\Controller\GenerateController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GenerateControllerFactory
 * @package Generate\Controller\Factory
 */
class GenerateControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GenerateControllerFactory|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $generateService = $container->get(GenerateService::class);
        return new GenerateController($generateService);
    }
}