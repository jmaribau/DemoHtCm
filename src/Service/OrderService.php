<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Event\OrderEvent;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderService.
 */
class OrderService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var CoffeeService
     */
    private $coffeeService;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * OrderService constructor.
     *
     * @param OrderRepository          $orderRepository
     * @param UserService              $userService
     * @param CoffeeService            $coffeeService
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        OrderRepository $orderRepository,
        UserService $userService,
        CoffeeService $coffeeService,
        EventDispatcherInterface $dispatcher
    ) {
        $this->orderRepository = $orderRepository;
        $this->userService = $userService;
        $this->coffeeService = $coffeeService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param int $orderId
     *
     * @throws EntityNotFoundException
     *
     * @return Order
     */
    public function getOrder(int $orderId): Order
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new EntityNotFoundException('Order with id '.$orderId.' does not exist!');
        }

        return $order;
    }

    /**
     * @return Order[]|null
     */
    public function getAllOrders(): ?array
    {
        return $this->orderRepository->findAll();
    }

    /**
     * @param int $userId
     * @param int $coffeeId
     * @param int $amount
     * @param int $quantity
     *
     * @throws EntityNotFoundException
     *
     * @return Order
     */
    public function addOrder(int $userId, int $coffeeId, int $amount, int $quantity): Order
    {
        $order = new Order();
        $user = $this->userService->getUser($userId);
        $coffee = $this->coffeeService->getCoffee($coffeeId);

        $order->setUser($user);
        $order->setCoffee($coffee);
        $order->setAmount($amount);
        $order->setQuantity($quantity);

        $this->orderRepository->save($order);

        $this->coffeeService->updateStockCoffee($coffeeId, $quantity);

        $event = new OrderEvent($order);
        $this->dispatcher->dispatch($event, OrderEvent::SET);

        return $order;
    }

    /**
     * @param int $orderId
     * @param int $userId
     * @param int $coffeeId
     * @param int $amount
     * @param int $quantity
     *
     * @throws EntityNotFoundException
     *
     * @return Order
     */
    public function updateOrder(int $orderId, int $userId, int $coffeeId, int $amount, int $quantity): Order
    {
        $order = $this->getOrder($orderId);
        $order->setUser($this->userService->getUser($userId));
        $order->setCoffee($this->coffeeService->getCoffee($coffeeId));
        $order->setAmount($amount);
        $order->setQuantity($quantity);

        $this->orderRepository->save($order);

        $event = new OrderEvent($order);
        $this->dispatcher->dispatch($event, OrderEvent::UPDATE);

        return $order;
    }

    /**
     * @param int $orderId
     *
     * @throws EntityNotFoundException
     */
    public function deleteOrder(int $orderId): void
    {
        $order = $this->getOrder($orderId);
        $this->orderRepository->delete($order);

        $event = new OrderEvent($order);
        $this->dispatcher->dispatch($event, OrderEvent::DELETE);
    }
}
