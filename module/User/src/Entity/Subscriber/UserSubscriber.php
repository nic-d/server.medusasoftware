<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/06/2018
 * Time: 13:39
 */

namespace User\Entity\Subscriber;

use User\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class UserSubscriber
 * @package User\Entity\Subscriber
 */
class UserSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::preUpdate];
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->getEntity() instanceof User) {
            // If the password has changed, hash it
            if ($eventArgs->hasChangedField('password')) {
                /** @var string $newValue */
                $newValue = $eventArgs->getNewValue('password');

                // If the new value is empty, let's just use the old value
                if (empty($newValue) || is_null($newValue)) {
                    $hashedValue = $eventArgs->getOldValue('password');
                } else {
                    $hashedValue = password_hash($newValue, PASSWORD_DEFAULT);
                }

                $eventArgs->setNewValue('password', $hashedValue);
            }
        }
    }
}