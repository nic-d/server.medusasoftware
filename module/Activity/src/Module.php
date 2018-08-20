<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 13:01
 */

namespace Activity;

use Zend\Mvc\MvcEvent;
use Activity\Event\RegisterEvents;
use Zend\EventManager\EventManager;

/**
 * Class Module
 * @package Activity
 */
class Module
{
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        /** @var RegisterEvents $registerListener */
        $registerListener = $event->getApplication()->getServiceManager()->get(RegisterEvents::class);

        /** @var EventManager $eventManager */
        $eventManager = $event->getApplication()->getEventManager();
        $registerListener->attach($eventManager);
    }
}