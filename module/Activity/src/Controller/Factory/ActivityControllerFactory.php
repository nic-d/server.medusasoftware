<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:51
 */

namespace Activity\Controller\Factory;

use Activity\Service\ActivityService;
use Interop\Container\ContainerInterface;
use Activity\Controller\ActivityController;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ActivityControllerFactory
 * @package Activity\Controller\Factory
 */
class ActivityControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ActivityController|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $activityService = $container->get(ActivityService::class);
        return new ActivityController($activityService);
    }
}