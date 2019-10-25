<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coffee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CoffeeFixtures.
 */
class CoffeeFixtures extends Fixture
{
    /**
     * @var AppDataFixtures
     */
    private $dataFixtures;

    /**
     * CoffeeFixtures constructor.
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
        foreach ($this->dataFixtures->getCoffeeData() as $item) {
            $coffee = new Coffee();
            $coffee->setName($item['name']);
            $coffee->setIntensity($item['intensity']);
            $coffee->setPrice($item['price']);
            $coffee->setStock($item['stock']);

            $manager->persist($coffee);

            $this->addReference('coffee_'.$item['id'], $coffee);
        }
        $manager->flush();
    }
}
