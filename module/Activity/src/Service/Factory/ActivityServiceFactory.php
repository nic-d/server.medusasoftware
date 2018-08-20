<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:52
 */

namespace Activity\Service\Factory;

use Activity\Service\ActivityService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ActivityServiceFactory
 * @package Activity\Service\Factory
 */
class ActivityServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ActivityService|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $eventManager = $container->get('EventManager');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ActivityService($entityManager, $eventManager);
    }
}