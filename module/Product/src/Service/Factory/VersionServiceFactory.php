<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:47
 */

namespace Product\Service\Factory;

use BsbFlysystem\Service\FilesystemManager;
use Product\Service\VersionService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VersionServiceFactory
 * @package Product\Service\Factory
 */
class VersionServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|VersionService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formManager = $container->get('FormElementManager');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $filesystem = $container->get(FilesystemManager::class)->get('files');


        return new VersionService($entityManager, $formManager, $filesystem);
    }
}