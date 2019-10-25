<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Coffee;
use App\Repository\CoffeeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class CoffeeRepositoryTest extends KernelTestCase
{
    /**
     * @var null|CoffeeRepository
     */
    private $coffeeRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$kernel->getContainer();
        /** @var EntityManager $entityManager */
        $entityManager = self::$container->get('doctrine');
        /** @var CoffeeRepository $repository */
        $repository = $entityManager->getRepository(Coffee::class);
        $this->coffeeRepository = $repository;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->coffeeRepository = null;
    }

    public function testSave(): void
    {
        $coffee = new Coffee();
        $coffee->setName('CoffeeX');
        $coffee->setIntensity(1);
        $coffee->setPrice(2);
        $coffee->setStock(3);
        $this->coffeeRepository->save($coffee);

        $this->assertEquals($coffee, $this->coffeeRepository->find($coffee->getId()));
    }

    public function testDelete(): void
    {
        /** @var Coffee $coffee */
        $coffee = $this->coffeeRepository->find(5);
        $this->coffeeRepository->delete($coffee);

        $this->assertNull($this->coffeeRepository->find(5));
    }
}
