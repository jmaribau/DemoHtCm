<?php

declare(strict_types=1);

namespace App\Manager\Logger;

use App\Entity\Coffee;

/**
 * Class CoffeeLogger.
 */
class CoffeeLogger extends BaseLogger
{
    public const SET = 'SET COFFEE';
    public const UPDATE = 'UPDATE COFFEE';
    public const DELETE = 'DELETE COFFEE';
    public const STOCK = 'ERROR SET ORDER: out of Stock';

    /**
     * @param Coffee $coffee
     */
    public function coffeeSet(Coffee $coffee): void
    {
        //$this->logger->info(self::SET .': ', [$this->user->getName(), $this->user->getRoles()]);
        $this->logger->info(self::SET.': ');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Coffee $coffee
     */
    public function coffeeUpdate(Coffee $coffee): void
    {
        // $this->logger->info(self::UPDATE .': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::UPDATE.': ');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Coffee $coffee
     */
    public function coffeeDelete(Coffee $coffee): void
    {
        // $this->logger->info(self::DELETE.': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::DELETE.': ');
        $this->logger->info($this->serializer->serialize($coffee, 'json'));
        $this->logger->info('');
    }

    public function coffeeOutOfStock(): void
    {
        $this->logger->info(self::STOCK);
        $this->logger->info('');
    }
}
