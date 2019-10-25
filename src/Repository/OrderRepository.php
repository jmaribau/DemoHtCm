<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Order::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Order $order
     */
    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @param Order $order
     */
    public function delete(Order $order): void
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }
}
