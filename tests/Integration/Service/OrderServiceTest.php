<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\DataFixtures\AppDataFixtures;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\CoffeeService;
use App\Service\OrderService;
use App\Service\UserService;
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
class OrderServiceTest extends KernelTestCase
{
    /**
     * @var array
     */
    private $dataFixtures;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var null|OrderRepository
     */
    private $orderRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$kernel->getContainer();

        /** @var EntityManager $entityManager */
        $entityManager = self::$container->get('doctrine');
        /** @var OrderRepository $repository */
        $repository = $entityManager->getRepository(Order::class);
        $this->orderRepository = $repository;
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::$container->get('debug.event_dispatcher');
        /** @var UserService $userService */
        $userService = self::$container->get('App\Service\UserService');
        /** @var CoffeeService $coffeeService */
        $coffeeService = self::$container->get('App\Service\CoffeeService');

        $this->orderService = new OrderService($this->orderRepository, $userService, $coffeeService, $eventDispatcher);
        $this->dataFixtures = (new AppDataFixtures())->getOrderDataForTest();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->orderRepository = null;
    }

    public function testGetOrder(): void
    {
        /** @var Order $order */
        $order = $this->orderService->getOrder(1);

        $this->assertEquals(Order::class, get_class($order));
        $this->assertEquals($this->orderRepository->find(1), $order);

        $this->expectException(EntityNotFoundException::class);
        $this->orderService->getOrder(999);
    }

    public function testGetAllOrders(): void
    {
        /** @var Order[] $orders */
        $orders = $this->orderService->getAllOrders();

        $this->assertCount(5, $orders);
        $this->assertContainsOnlyInstancesOf(Order::class, $orders);
        $this->assertEquals($this->orderRepository->findAll(), $orders);
    }

    public function testAddOrder(): void
    {
        $expected = $this->dataFixtures[1];

        $order = $this->orderService->addOrder(
            $expected['user'],
            $expected['coffee'],
            $expected['amount'],
            $expected['quantity']
        );

        $this->assertEquals($expected['user'], $order->getUser()->getId());
        $this->assertEquals($expected['coffee'], $order->getCoffee()->getId());
        $this->assertEquals($expected['amount'], $order->getAmount());
        $this->assertEquals($expected['quantity'], $order->getQuantity());
    }

    public function testUpdateOrder(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $expected = $this->dataFixtures[1];
        $order = $this->orderService->updateOrder(
            1,
            $expected['user'] + 1,
            $expected['coffee'] + 1,
            $expected['amount'] + 1,
            $expected['quantity'] + 1
        );

        $this->assertEquals($expected['user'] + 1, $order->getUser()->getId());
        $this->assertEquals($expected['coffee'] + 1, $order->getCoffee()->getId());
        $this->assertEquals($expected['amount'] + 1, $order->getAmount());
        $this->assertEquals($expected['quantity'] + 1, $order->getQuantity());

        $this->orderService->updateOrder(999, 0, 0, 0, 0);
    }

    public function testDeteleOrder(): void
    {
        $this->orderService->deleteOrder(5);
        $orderCheck = $this->orderRepository->find(5);

        $this->assertNull($orderCheck);

        $this->expectException(EntityNotFoundException::class);
        $this->orderService->deleteOrder(999);
    }
}
