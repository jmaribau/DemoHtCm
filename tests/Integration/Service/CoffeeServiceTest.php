<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\DataFixtures\AppDataFixtures;
use App\Entity\Coffee;
use App\Repository\CoffeeRepository;
use App\Service\CoffeeService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class CoffeeServiceTest extends KernelTestCase
{
    /**
     * @var array
     */
    private $dataFixtures;

    /**
     * @var CoffeeService
     */
    private $coffeeService;

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
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::$container->get('debug.event_dispatcher');
        $this->coffeeService = new CoffeeService($this->coffeeRepository, $eventDispatcher);
        $this->dataFixtures = (new AppDataFixtures())->getCoffeeDataForTest();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->coffeeRepository = null;
    }

    public function testGetCoffee(): void
    {
        /** @var Coffee $coffee */
        $coffee = $this->coffeeService->getCoffee(1);

        $this->assertEquals(Coffee::class, get_class($coffee));
        $this->assertEquals($this->coffeeRepository->find(1), $coffee);

        $this->expectException(EntityNotFoundException::class);
        $this->coffeeService->getCoffee(999);
    }

    public function testGetAllCoffees(): void
    {
        /** @var Coffee[] $coffees */
        $coffees = $this->coffeeService->getAllCoffees();

        $this->assertCount(5, $coffees);
        $this->assertContainsOnlyInstancesOf(Coffee::class, $coffees);
        $this->assertEquals($this->coffeeRepository->findAll(), $coffees);
    }

    public function testAddCoffee(): void
    {
        $expected = $this->dataFixtures[1];

        $coffee = $this->coffeeService->addCoffee(
            $expected['name'],
            $expected['intensity'],
            $expected['price'],
            $expected['stock']
        );

        $this->assertEquals($expected['name'], $coffee->getName());
        $this->assertEquals($expected['intensity'], $coffee->getIntensity());
        $this->assertEquals($expected['price'], $coffee->getPrice());
        $this->assertEquals($expected['stock'], $coffee->getStock());
    }

    public function testUpdateCoffee(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $expected = $this->dataFixtures[1];
        $coffee = $this->coffeeService->updateCoffee(
            1,
            $expected['name'].'_mod',
            $expected['intensity'] + 1,
            $expected['price'] + 1,
            $expected['stock'] + 1
        );

        $this->assertEquals($expected['name'].'_mod', $coffee->getName());
        $this->assertEquals($expected['intensity'] + 1, $coffee->getIntensity());
        $this->assertEquals($expected['price'] + 1, $coffee->getPrice());
        $this->assertEquals($expected['stock'] + 1, $coffee->getStock());

        $this->coffeeService->updateCoffee(999, '', 0, 0, 0);
    }

    public function testDeteleCoffee(): void
    {
        $this->coffeeService->deleteCoffee(4);
        $coffeeCheck = $this->coffeeRepository->find(4);

        $this->assertNull($coffeeCheck);

        $this->expectException(EntityNotFoundException::class);
        $this->coffeeService->deleteCoffee(999);
    }
}
