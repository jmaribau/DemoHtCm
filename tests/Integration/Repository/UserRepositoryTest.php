<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group integration
 * @covers
 *
 * @internal
 */
class UserRepositoryTest extends KernelTestCase
{
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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->userRepository = null;
    }

    public function testSave(): void
    {
        $user = new User();
        $user->setUsername('UserX');
        $user->setPassword('qwerty');
        $user->setRoles([]);

        $this->userRepository->save($user);

        $this->assertEquals($user, $this->userRepository->find($user->getId()));
    }

    public function testDelete(): void
    {
        /** @var User $user */
        $user = $this->userRepository->find(5);
        $this->userRepository->delete($user);
        $this->assertNull($this->userRepository->find(5));
    }
}
