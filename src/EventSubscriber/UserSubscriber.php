<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserEvent;
use App\Manager\Logger\UserLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderSubscriber.
 */
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserLogger
     */
    private $userLogger;

    public function __construct(UserLogger $userLogger)
    {
        $this->userLogger = $userLogger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'user.set' => 'onUserSet',
            'user.update' => 'onUserUpdate',
            'user.delete' => 'onUserDelete',
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function onUserSet(UserEvent $event): void
    {
        $this->userLogger->userSet($event->getUser());
    }

    /**
     * @param UserEvent $event
     */
    public function onUserUpdate(UserEvent $event): void
    {
        $this->userLogger->userUpdate($event->getUser());
    }

    /**
     * @param UserEvent $event
     */
    public function onUserDelete(UserEvent $event): void
    {
        $this->userLogger->userDelete($event->getUser());
    }
}
