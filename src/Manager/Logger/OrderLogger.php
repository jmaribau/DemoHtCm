<?php

declare(strict_types=1);

namespace App\Manager\Logger;

use App\Entity\Order;

/**
 * Class CoffeeLogger.
 */
class OrderLogger extends BaseLogger
{
    public const SET = 'SET ORDER';
    public const UPDATE = 'UPDATE ORDER';
    public const DELETE = 'DELETE ORDER';

    /**
     * @param Order $order
     */
    public function orderSet(Order $order): void
    {
        $coffee = $order->getCoffee();

        // $this->logger->info(self::SET . $order->getQuantity() . ' units of coffee', [$this->user->getUserName()]);
        $this->logger->info(self::SET.':  order '.$order->getQuantity().' units of coffee');
        $this->logger->info($this->serializer->serialize($order, 'json'));
        $this->logger->info(CoffeeLogger::UPDATE.' ('.$coffee->getId().'): '
            .$order->getQuantity().'units of coffee consumed');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');

        if (0 >= $coffee->getStock()) {
            $this->logger->info('ERROR '.self::SET.' : out of stock');
            $this->logger->info('');
        }
    }

    /**
     * @param Order $order
     */
    public function orderUpdate(Order $order): void
    {
        $coffee = $order->getCoffee();

        // $this->logger->info(self::UPDATE . $order->getQuantity() . ' units of coffee', [$this->user->getUserName()]);
        $this->logger->info(self::UPDATE.': '.$order->getQuantity().' units of coffee');
        $this->logger->info($this->serializer->serialize($order, 'json'));
        $this->logger->info(CoffeeLogger::UPDATE.' ('.$coffee->getId().'): '
            .$order->getQuantity().' units of coffee modified');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');

        if (0 >= $coffee->getStock()) {
            $this->logger->info('ERROR '.self::UPDATE.': out of stock');
            $this->logger->info('');
        }
    }

    /**
     * @param Order $order
     */
    public function orderDelete(Order $order): void
    {
        $coffee = $order->getCoffee();

        // $this->logger->info(self::DELETE . $order->getQuantity() . ' units of coffee', [$this->user->getUserName()]);
        $this->logger->info(self::DELETE.': '.$order->getQuantity().' units of coffee');
        $this->logger->info($this->serializer->serialize($order, 'json'));
        $this->logger->info(CoffeeLogger::UPDATE.' : '.$order->getQuantity().' units of coffee restored');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');
    }
}
