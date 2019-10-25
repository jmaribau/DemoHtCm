<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Coffee;
use App\Entity\Order;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @covers
 *
 * @internal
 */
class OrderTest extends TestCase
{
    public function testOrderCreate(): void
    {
        $order = new Order();

        $this->assertEquals(Order::class, get_class($order));
    }

    public function testOrderCheckProperties(): void
    {
        $order = new Order();
        $order->setUser($user = new User());
        $order->setCoffee($coffee = new Coffee());
        $order->setAmount(5);
        $order->setQuantity(6);

        $this->assertEquals('object', gettype($order->getUser()));
        $this->assertEquals($user, $order->getUser());

        $this->assertEquals('object', gettype($order->getCoffee()));
        $this->assertEquals($coffee, $order->getCoffee());

        $this->assertEquals('integer', gettype($order->getAmount()));
        $this->assertEquals(5, $order->getAmount());

        $this->assertEquals('integer', gettype($order->getQuantity()));
        $this->assertEquals(6, $order->getQuantity());
    }
}
