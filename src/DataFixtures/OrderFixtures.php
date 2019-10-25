<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coffee;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CoffeeFixtures.
 */
class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var AppDataFixtures
     */
    private $dataFixtures;

    /**
     * OrderFixtures constructor.
     *
     * @param AppDataFixtures $dataFixtures
     */
    public function __construct(AppDataFixtures $dataFixtures)
    {
        $this->dataFixtures = $dataFixtures;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadOrders($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadOrders(ObjectManager $manager): void
    {
        foreach ($this->dataFixtures->getOrderData() as $item) {
            /** @var User $user */
            $user = $this->getReference('user_'.$item['user']);

            /** @var Coffee $coffee */
            $coffee = $this->getReference('coffee_'.$item['coffee']);

            $reservation = new Order();

            $reservation->setUser($user);
            $reservation->setCoffee($coffee);
            $reservation->setAmount($item['amount']);
            $reservation->setQuantity($item['quantity']);

            $manager->persist($reservation);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CoffeeFixtures::class,
        ];
    }
}
