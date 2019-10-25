<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures.
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var AppDataFixtures
     */
    private $dataFixtures;

    /**
     * AppFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AppDataFixtures              $dataFixtures
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, AppDataFixtures $dataFixtures)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->dataFixtures = $dataFixtures;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->dataFixtures->getUserData() as $item) {
            $user = new User();
            $user->setUsername($item['username']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $item['password']));
            $user->setRoles($item['roles']);
            $manager->persist($user);

            $this->addReference('user_'.$item['id'], $user);
        }
        $manager->flush();
    }
}
