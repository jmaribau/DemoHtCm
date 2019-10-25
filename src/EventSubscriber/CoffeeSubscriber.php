<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\CoffeeEvent;
use App\Manager\Logger\CoffeeLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CoffeeSubscriber.
 */
class CoffeeSubscriber implements EventSubscriberInterface
{
    /**
     * @var CoffeeLogger
     */
    private $coffeeLogger;

    public function __construct(CoffeeLogger $coffeeLogger)
    {
        $this->coffeeLogger = $coffeeLogger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'coffee.set' => 'onCoffeeSet',
            'coffee.update' => 'onCoffeeUpdate',
            'coffee.delete' => 'onCoffeeDelete',
            'coffee.stock' => 'onCoffeeStock',
        ];
    }

    /**
     * @param CoffeeEvent $event
     */
    public function onCoffeeSet(CoffeeEvent $event): void
    {
        $this->coffeeLogger->coffeeSet($event->getCoffee());
    }

    /**
     * @param CoffeeEvent $event
     */
    public function onCoffeeUpdate(CoffeeEvent $event): void
    {
        $this->coffeeLogger->coffeeUpdate($event->getCoffee());
    }

    /**
     * @param CoffeeEvent $event
     */
    public function onCoffeeDelete(CoffeeEvent $event): void
    {
        $this->coffeeLogger->coffeeDelete($event->getCoffee());
    }

    /**
     * @param CoffeeEvent $event
     */
    public function onCoffeeStock(CoffeeEvent $event): void
    {
        $coffee = $event->getCoffee();
        if (0 > $coffee->getStock()) {
            $this->coffeeLogger->coffeeOutOfStock();
        }
    }
}
