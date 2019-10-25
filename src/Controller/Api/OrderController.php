<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\OrderService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Order Controller.
 */
class OrderController extends AbstractFOSRestController
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Retrieves a collection of Order resource.
     *
     * @Rest\Get("/orders")
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Order")
     */
    public function getOrders(): View
    {
        $orders = $this->orderService->getAllOrders();

        return new View($orders, Response::HTTP_OK);
    }

    /**
     * Retrieves Order resource.
     *
     * @Rest\Get("/orders/{orderId}")
     *
     * @param int $orderId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Order")
     */
    public function getOrder(int $orderId): View
    {
        $order = $this->orderService->getOrder($orderId);

        return new View($order, Response::HTTP_OK);
    }

    /**
     * Creates Order resource.
     *
     * @Rest\Post("/orders")
     *
     * @param Request $request
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_USER")
     *
     * @SWG\Tag(name="Order")
     */
    public function postOrder(Request $request): View
    {
        $order = $this->orderService->addOrder(
            $request->get('user'),
            $request->get('coffee'),
            $request->get('amount'),
            $request->get('quantity')
        );

        return new View($order, Response::HTTP_CREATED);
    }

    /**
     * Replaces Order resource.
     *
     * @Rest\Put("/orders/{orderId}")
     *
     * @param int     $orderId
     * @param Request $request
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Order")
     */
    public function putOrder(int $orderId, Request $request): View
    {
        $order = $this->orderService->updateOrder(
            $orderId,
            $request->get('user'),
            $request->get('coffee'),
            $request->get('amount'),
            $request->get('quantity')
        );

        return new View($order, Response::HTTP_OK);
    }

    /**
     * Removes the Order resource.
     *
     * @Rest\Delete("/orders/{orderId}")
     *
     * @param int $orderId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Order")
     */
    public function deleteOrder(int $orderId): View
    {
        $this->orderService->deleteOrder($orderId);

        return new View([], Response::HTTP_NO_CONTENT);
    }
}
