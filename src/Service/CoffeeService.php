<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coffee;
use App\Event\CoffeeEvent;
use App\Repository\CoffeeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CoffeeService.
 */
class CoffeeService
{
    /**
     * @var CoffeeRepository
     */
    private $coffeeRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * CoffeeService constructor.
     *
     * @param CoffeeRepository         $coffeeRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(CoffeeRepository $coffeeRepository, EventDispatcherInterface $dispatcher)
    {
        $this->coffeeRepository = $coffeeRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param int $coffeeId
     *
     * @throws EntityNotFoundException
     *
     * @return Coffee
     */
    public function getCoffee(int $coffeeId): Coffee
    {
        $coffee = $this->coffeeRepository->find($coffeeId);
        if (!$coffee) {
            throw new EntityNotFoundException('Coffee with id '.$coffeeId.' does not exist!');
        }

        return $coffee;
    }

    /**
     * @return Coffee[]|null
     */
    public function getAllCoffees(): ?array
    {
        return $this->coffeeRepository->findAll();
    }

    /**
     * @param string $name
     * @param int    $intensity
     * @param int    $price
     * @param int    $stock
     *
     * @return Coffee
     */
    public function addCoffee(string $name, int $intensity, int $price, int $stock): Coffee
    {
        $coffee = new Coffee();
        $coffee->setName($name);
        $coffee->setIntensity($intensity);
        $coffee->setPrice($price);
        $coffee->setStock($stock);

        $this->coffeeRepository->save($coffee);

        $event = new CoffeeEvent($coffee);
        $this->dispatcher->dispatch($event, CoffeeEvent::SET);

        return $coffee;
    }

    /**
     * @param int    $coffeeId
     * @param string $name
     * @param int    $intensity
     * @param int    $price
     * @param int    $stock
     *
     * @throws EntityNotFoundException
     *
     * @return Coffee
     */
    public function updateCoffee(int $coffeeId, string $name, int $intensity, int $price, int $stock): Coffee
    {
        $coffee = $this->getCoffee($coffeeId);

        $coffee->setName($name);
        $coffee->setIntensity($intensity);
        $coffee->setPrice($price);
        $coffee->setStock($stock);

        $this->coffeeRepository->save($coffee);

        $event = new CoffeeEvent($coffee);
        $this->dispatcher->dispatch($event, CoffeeEvent::UPDATE);

        return $coffee;
    }

    /**
     * @param int $coffeeId
     *
     * @throws EntityNotFoundException
     */
    public function deleteCoffee(int $coffeeId): void
    {
        $coffee = $this->getCoffee($coffeeId);
        $this->coffeeRepository->delete($coffee);

        $event = new CoffeeEvent($coffee);
        $this->dispatcher->dispatch($event, CoffeeEvent::DELETE);
    }

    /**
     * @param int $coffeeId
     * @param int $stock
     *
     * @throws EntityNotFoundException
     *
     * @return Coffee
     */
    public function updateStockCoffee(int $coffeeId, int $stock): Coffee
    {
        $coffee = $this->getCoffee($coffeeId);
        $newStock = $coffee->getStock() - $stock;
        $coffee->setStock($newStock);

        $this->coffeeRepository->save($coffee);

        $event = new CoffeeEvent($coffee);
        $this->dispatcher->dispatch($event, CoffeeEvent::STOCK);

        return $coffee;
    }
}
