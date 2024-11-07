<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Order\Enum\OrderStatusEnum;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Attribute\Groups;

#[Entity(repositoryClass: OrderRepository::class)]
#[Table(name: 'cart_order')]
class Order extends AbstractEntity
{
    #[Column(type: 'string', enumType: OrderStatusEnum::class)]
    #[Groups(['order:list'])]
    private OrderStatusEnum $status;
    #[Column(type: 'integer')]
    #[Groups(['order:list'])]
    private int $totalPrice;
    #[Column(type: 'string')]
    #[Groups(['order:list'])]
    private string $billingAddress;
    #[Column(type: 'string')]
    #[Groups(['order:list'])]
    private string $shippingAddress;
    #[OneToOne(targetEntity: Cart::class, mappedBy: 'order')]
    #[Groups(['order:list'])]
    private Cart $cart;
    #[ManyToOne(targetEntity: User::class)]
    private User $user;

    public function getStatus(): OrderStatusEnum
    {
        return $this->status;
    }

    public function setStatus(OrderStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(string $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
