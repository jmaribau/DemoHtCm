<?php

declare(strict_types=1);

namespace App\DataFixtures;

/**
 * Class CoffeeFixtures.
 */
class AppDataFixtures
{
    /**
     * @return array
     */
    public function getCoffeeData(): array
    {
        return [
            1 => ['id' => 1, 'name' => 'Cappuccino', 'intensity' => 10, 'price' => 5, 'stock' => 100],
            2 => ['id' => 2, 'name' => 'Latte',      'intensity' => 9,  'price' => 6,  'stock' => 90],
            3 => ['id' => 3, 'name' => 'Espresso',   'intensity' => 8,  'price' => 7,  'stock' => 80],
            4 => ['id' => 4, 'name' => 'American',  'intensity' => 7,  'price' => 8,  'stock' => 70],
            5 => ['id' => 5, 'name' => 'Macchiato',  'intensity' => 6,  'price' => 9,  'stock' => 60],
        ];
    }

    public function getCoffeeDataForTest(): array
    {
        return $this->getCoffeeData();
    }

    /**
     * @return array
     */
    public function getUserData(): array
    {
        return [
            1 => ['id' => 1, 'username' => 'admin',      'password' => 'pw_ad', 'roles' => ['ROLE_ADMIN']],
            2 => ['id' => 2, 'username' => 'customer_1', 'password' => 'pw_c1', 'roles' => ['ROLE_USER']],
            3 => ['id' => 3, 'username' => 'customer_2', 'password' => 'pw_c2', 'roles' => ['ROLE_USER']],
            4 => ['id' => 4, 'username' => 'customer_3', 'password' => 'pw_c3', 'roles' => ['ROLE_USER']],
            5 => ['id' => 5, 'username' => 'customer_4', 'password' => 'pw_c4', 'roles' => ['ROLE_USER']],
        ];
    }

    /**
     * @return array
     */
    public function getUserDataForTest(): array
    {
        $items = [];
        foreach ($this->getUserData() as $index => $item) {
            unset($item['password']);
            $items[$index] = $item;
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getOrderData(): array
    {
        return [
            1 => ['id' => 1, 'user' => 1, 'coffee' => 1, 'amount' => 10, 'quantity' => 2],
            2 => ['id' => 2, 'user' => 2, 'coffee' => 1, 'amount' => 20, 'quantity' => 4],
            3 => ['id' => 3, 'user' => 3, 'coffee' => 2, 'amount' => 60, 'quantity' => 10],
            4 => ['id' => 4, 'user' => 4, 'coffee' => 2, 'amount' => 30, 'quantity' => 5],
            5 => ['id' => 5, 'user' => 4, 'coffee' => 3, 'amount' => 15, 'quantity' => 2],        ];
    }

    /**
     * @return array
     */
    public function getOrderDataForTest(): array
    {
        return $this->getOrderData();
    }
}
