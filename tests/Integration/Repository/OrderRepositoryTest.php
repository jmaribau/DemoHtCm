<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Coffee;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CoffeeRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class OrderRepositoryTest extends KernelTestCase
{
    /**
     * @var null|OrderRepository
     */
    private $orderRepository;

    /**
     * @var CoffeeRepository
     */
    private $coffeeRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$kernel->getContainer();
        /** @var EntityManager $entityManager */
        $entityManager = self::$container->get('doctrine');
        /** @var OrderRepository $repository */
        $repository = $entityManager->getRepository(Order::class);
        $this->orderRepository = $repository;
        /** @var CoffeeRepository $repository */
        $repository = $entityManager->getRepository(Coffee::class);
        $this->coffeeRepository = $repository;
        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository(User::class);
        $this->userRepository = $repository;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->orderRepository = null;
    }

    public function testSave(): void
    {
        $coffee = $this->coffeeRepository->find(2);
        $user = $this->userRepository->find(2);

        $order = new Order();
        $order->setUser($user);
        $order->setCoffee($coffee);
        $order->setAmount(5);
        $order->setQuantity(10);

        $this->orderRepository->save($order);

        $this->assertEquals($order, $this->orderRepository->find($order->getId()));
    }

    public function testDelete(): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->find(5);
        $this->orderRepository->delete($order);

        $this->assertNull($this->orderRepository->find(5));
    }
}
