<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\UserService;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class User Controller.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieves a collection of User resource.
     *
     * @Rest\Get("/users")
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function getUsers(): View
    {
        $users = $this->userService->getAllUsers();

        return new View($users, Response::HTTP_OK);
    }

    /**
     * Retrieves User resource.
     *
     * @Rest\Get("/users/{userId}")
     *
     * @param int $userId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function getApiUser(int $userId): View
    {
        $user = $this->userService->getUser($userId);

        return new View($user, Response::HTTP_OK);
    }

    /**
     * Creates User resource.
     *
     * @Rest\Post("/users")
     *
     * @param Request $request
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function postUser(Request $request): View
    {
        $user = $this->userService->addUser(
            $request->get('username'),
            $request->get('password'),
            userService::DEFAULT_ROLE
        );

        return new View($user, Response::HTTP_CREATED);
    }

    /**
     * Replaces User resource.
     *
     * @Rest\Put("/users/{userId}")
     *
     * @param int     $userId
     * @param Request $request
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function putUser(int $userId, Request $request): View
    {
        $user = $this->userService->updateUser(
            $userId,
            $request->get('username'),
            $request->get('password'),
            userService::DEFAULT_ROLE
        );

        return new View($user, Response::HTTP_OK);
    }

    /**
     * Removes the User resource.
     *
     * @Rest\Delete("/users/{userId}")
     *
     * @param int $userId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function deleteUser(int $userId): View
    {
        $this->userService->deleteUser($userId);

        return new View([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieves User orders.
     *
     * @Rest\Get("/users/{userId}/orders")
     *
     * @param int $userId
     *
     * @throws EntityNotFoundException
     *
     * @return View
     *
     * @SWG\Tag(name="User")
     */
    public function getUserOrders(int $userId): View
    {
        $user = $this->userService->getUser($userId);

        return new View($user->getOrders(), Response::HTTP_OK);
    }
}
