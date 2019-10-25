<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OrderEvent.
 */
class OrderEvent extends Event
{
    public const SET = 'order.set';
    public const UPDATE = 'order.update';
    public const DELETE = 'order.delete';

    /**
     * @var Order
     */
    public $order;

    /**
     * OrderEvent constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }
}
