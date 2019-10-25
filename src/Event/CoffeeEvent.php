<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Coffee;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CoffeeEvent.
 */
class CoffeeEvent extends Event
{
    public const SET = 'coffee.set';
    public const UPDATE = 'coffee.update';
    public const DELETE = 'coffee.delete';
    public const STOCK = 'coffee.stock';

    /**
     * @var Coffee
     */
    public $coffee;

    /**
     * CoffeeEvent constructor.
     *
     * @param Coffee $coffee
     */
    public function __construct(Coffee $coffee)
    {
        $this->coffee = $coffee;
    }

    /**
     * @return Coffee
     */
    public function getCoffee(): Coffee
    {
        return $this->coffee;
    }
}
