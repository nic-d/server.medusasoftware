<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 13:05
 */

namespace Activity\Event\Factory;

use Activity\Event\RegisterEvents;
use Activity\Service\ActivityService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RegisterEventsFactory
 * @package Activity\Event\Factory
 */
class RegisterEventsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RegisterEvents|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $activityService = $container->get(ActivityService::class);
        return new RegisterEvents($activityService);
    }
}