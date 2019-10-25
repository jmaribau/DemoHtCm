<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Event\UserEvent;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService.
 */
class UserService
{
    public const DEFAULT_ROLE = ['ROLE_USER'];

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * UserService constructor.
     *
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventDispatcherInterface     $dispatcher
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EventDispatcherInterface $dispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param int $userId
     *
     * @throws EntityNotFoundException
     *
     * @return User
     */
    public function getUser(int $userId): User
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }

        return $user;
    }

    /**
     * @return User[]|null
     */
    public function getAllUsers(): ?array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param string $username
     * @param string $password
     * @param array  $roles
     *
     * @return User
     */
    public function addUser(string $username, string $password, array $roles): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles($roles);

        $this->userRepository->save($user);

        $event = new UserEvent($user);
        $this->dispatcher->dispatch($event, UserEvent::SET);

        return $user;
    }

    /**
     * @param int    $userId
     * @param string $username
     * @param string $password
     * @param array  $roles
     *
     * @throws EntityNotFoundException
     *
     * @return User
     */
    public function updateUser(int $userId, string $username, string $password, array $roles): User
    {
        $user = $this->getUser($userId);
        $user->setUsername($username);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles($roles);

        $this->userRepository->save($user);

        $event = new UserEvent($user);
        $this->dispatcher->dispatch($event, UserEvent::UPDATE);

        return $user;
    }

    /**
     * @param int $userId
     *
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->getUser($userId);
        $this->userRepository->delete($user);

        $event = new UserEvent($user);
        $this->dispatcher->dispatch($event, UserEvent::DELETE);
    }
}
