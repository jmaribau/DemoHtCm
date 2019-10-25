<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Table(name="shop_order")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     *
     * @Type("integer")
     * @Accessor(getter="serializeUser", setter="setUser")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Coffee", inversedBy="orders", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Coffee
     *
     * @Type("integer")
     * @Accessor(getter="serializeCoffee", setter="setCoffee")
     */
    private $coffee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCoffee(): Coffee
    {
        return $this->coffee;
    }

    public function setCoffee(Coffee $coffee): self
    {
        $this->coffee = $coffee;

        return $this;
    }

    public function serializeUser(): ?int
    {
        return $this->user->getId();
    }

    public function serializeCoffee(): ?int
    {
        return $this->coffee->getId();
    }
}
