<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\DataFixtures\AppDataFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * @var array
     */
    private $dataFixtures;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var null|UserRepository
     */
    private $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        self::$kernel->getContainer();
        /** @var EntityManager $entityManager */
        $entityManager = self::$container->get('doctrine');
        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository(User::class);
        $this->userRepository = $repository;
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::$container->get('debug.event_dispatcher');
        /** @var UserPasswordEncoderInterface $passwordEncoder */
        $passwordEncoder = self::$container->get('security.user_password_encoder.generic');
        $this->userService = new UserService($this->userRepository, $passwordEncoder, $eventDispatcher);
        $this->dataFixtures = (new AppDataFixtures())->getUserData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->userRepository = null;
    }

    public function testGetUser(): void
    {
        /** @var User $user */
        $user = $this->userService->getUser(1);

        $this->assertEquals(User::class, get_class($user));
        $this->assertEquals($this->userRepository->find(1), $user);

        $this->expectException(EntityNotFoundException::class);
        $this->userService->getUser(999);
    }

    public function testGetAllUsers(): void
    {
        /** @var User[] $users */
        $users = $this->userService->getAllUsers();

        $this->assertCount(5, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
        $this->assertEquals($this->userRepository->findAll(), $users);
    }

    public function testAddUser(): void
    {
        $expected = $this->dataFixtures[1];
        $expected['username'] .= '_new';

        $user = $this->userService->addUser($expected['username'], $expected['password'], $expected['roles']);
        $this->assertEquals($this->userRepository->findOneBy(['username' => $expected['username']]), $user);
    }

    public function testUpdateUser(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $expected = $this->dataFixtures[2];
        $expected['username'] .= '_mod';

        $user = $this->userService->updateUser(2, $expected['username'], 'none', []);

        $this->assertEquals($this->userRepository->findOneBy(['username' => $expected['username']]), $user);

        $this->userService->updateUser(999, '', '', []);
    }

    public function testDeleteUser(): void
    {
        $this->userService->deleteUser(5);
        $userCheck = $this->userRepository->find(5);

        $this->assertNull($userCheck);

        $this->expectException(EntityNotFoundException::class);
        $this->userService->deleteUser(999);
    }
}
