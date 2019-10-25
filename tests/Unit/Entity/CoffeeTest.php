<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Coffee;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @covers
 *
 * @internal
 */
class CoffeeTest extends TestCase
{
    public function testCoffeeCreate(): void
    {
        $coffee = new Coffee();

        $this->assertEquals(Coffee::class, get_class($coffee));
    }

    public function testCoffeeCheckProperties(): void
    {
        $coffee = new Coffee();
        $coffee->setName('Name');
        $coffee->setIntensity(5);
        $coffee->setPrice(6);
        $coffee->setStock(7);

        $this->assertEquals('string', gettype($coffee->getName()));
        $this->assertEquals('Name', $coffee->getName());

        $this->assertEquals('integer', gettype($coffee->getIntensity()));
        $this->assertEquals(5, $coffee->getIntensity());

        $this->assertEquals('integer', gettype($coffee->getPrice()));
        $this->assertEquals(6, $coffee->getPrice());

        $this->assertEquals('integer', gettype($coffee->getStock()));
        $this->assertEquals(7, $coffee->getStock());
    }
}
