<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Coffee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Coffee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coffee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coffee[]    findAll()
 * @method Coffee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoffeeRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Coffee::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Coffee $coffee
     */
    public function save(Coffee $coffee): void
    {
        $this->entityManager->persist($coffee);
        $this->entityManager->flush();
    }

    /**
     * @param Coffee $coffee
     */
    public function delete(Coffee $coffee): void
    {
        $this->entityManager->remove($coffee);
        $this->entityManager->flush();
    }
}
