<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 13:05
 */

namespace Activity\Event;

use Zend\EventManager\Event;
use Activity\Service\ActivityService;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class RegisterEvents
 * @package Activity\Event
 */
class RegisterEvents implements ListenerAggregateInterface
{
    /** @var array $listeners */
    protected $listeners = [];

    /** @var ActivityService $activityService */
    protected $activityService;

    /**
     * RegisterEvents constructor.
     * @param ActivityService $activityService
     */
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @param EventManagerInterface $event
     * @param int $priority
     */
    public function attach(EventManagerInterface $event, $priority = 1)
    {
        $this->listeners[] = $event->getSharedManager()->attach('*', 'activity.log', [$this, 'onActivityLogEvent'], $priority);
    }

    /**
     * @param EventManagerInterface $event
     */
    public function detach(EventManagerInterface $event)
    {
        foreach ($this->listeners as $key => $value) {
            if ($event->detach($value)) {
                unset($this->listeners[$key]);
            }
        }
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    public function onActivityLogEvent(Event $event)
    {
        $this->activityService->log([
            'message'   => $event->getParam('message'),
            'ipAddress' => $event->getParam('ipAddress'),
        ]);
    }
}