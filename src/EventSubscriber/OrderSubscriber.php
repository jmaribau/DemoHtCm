<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\OrderEvent;
use App\Manager\Logger\OrderLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderSubscriber.
 */
class OrderSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderLogger
     */
    private $orderLogger;

    public function __construct(OrderLogger $orderLogger)
    {
        $this->orderLogger = $orderLogger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'order.set' => 'onOrderSet',
            'order.update' => 'onOrderUpdate',
            'order.delete' => 'onOrderDelete',
        ];
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderSet(OrderEvent $event): void
    {
        $this->orderLogger->orderSet($event->getOrder());
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderUpdate(OrderEvent $event): void
    {
        $this->orderLogger->orderUpdate($event->getOrder());
    }

    /**
     * @param OrderEvent $event
     */
    public function onOrderDelete(OrderEvent $event): void
    {
        $this->orderLogger->orderDelete($event->getOrder());
    }
}
