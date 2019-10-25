<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\CoffeeService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Coffee Controller.
 */
class CoffeeController extends AbstractFOSRestController
{
    /**
     * @var CoffeeService
     */
    private $coffeeService;

    /**
     * CoffeeController constructor.
     *
     * @param CoffeeService $coffeeService
     */
    public function __construct(CoffeeService $coffeeService)
    {
        $this->coffeeService = $coffeeService;
    }

    /**
     * Retrieves a collection of Coffee resource.
     *
     * @Rest\Get("/coffees")
     *
     * @return View
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @SWG\Tag(name="Coffee")
     */
    public function getCoffees(): View
    {
        $coffees = $this->coffeeService->getAllCoffees();

        return new View($coffees, Response::HTTP_OK);
    }

    /**
     * Retrieves Coffee resource.
     *
     * @Rest\Get("/coffees/{coffeeId}")
     *
     * @param int $coffeeId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @SWG\Tag(name="Coffee")
     */
    public function getCoffee(int $coffeeId): View
    {
        $coffee = $this->coffeeService->getCoffee($coffeeId);

        return new View($coffee, Response::HTTP_OK);
    }

    /**
     * Creates Coffee resource.
     *
     * @Rest\Post("/coffees")
     *
     * @param Request $request
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Coffee")
     */
    public function postCoffee(Request $request): View
    {
        $coffee = $this->coffeeService->addCoffee(
            $request->get('name'),
            $request->get('intensity'),
            $request->get('price'),
            $request->get('stock')
        );

        return new View($coffee, Response::HTTP_CREATED);
    }

    /**
     * Replaces Coffee resource.
     *
     * @Rest\Put("/coffees/{coffeeId}")
     *
     * @param int     $coffeeId
     * @param Request $request
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Coffee")
     */
    public function putCoffee(int $coffeeId, Request $request): View
    {
        $coffee = $this->coffeeService->updateCoffee(
            $coffeeId,
            $request->get('name'),
            $request->get('intensity'),
            $request->get('price'),
            $request->get('stock')
        );

        return new View($coffee, Response::HTTP_OK);
    }

    /**
     * Removes the Coffee resource.
     *
     * @Rest\Delete("/coffees/{coffeeId}")
     *
     * @param int $coffeeId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Coffee")
     */
    public function deleteCoffee(int $coffeeId): View
    {
        $this->coffeeService->deleteCoffee($coffeeId);

        return new View([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieves Coffee orders.
     *
     * @Rest\Get("/coffees/{coffeeId}/orders")
     *
     * @param int $coffeeId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @SWG\Tag(name="Coffee")
     */
    public function getCoffeeOrders(int $coffeeId): View
    {
        $coffee = $this->coffeeService->getCoffee($coffeeId);

        return new View($coffee->getOrders(), Response::HTTP_OK);
    }
}
