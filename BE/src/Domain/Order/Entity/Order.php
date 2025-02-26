<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Order\Repository\OrderRepository;
use App\Domain\User\Entity\Guest;
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
    #[Column(type: 'string')]
    #[Groups(['order:list'])]
    private string $status;
    #[Column(type: 'integer')]
    #[Groups(['order:list'])]
    private int $totalPrice;
    #[Column(type: 'string')]
    #[Groups(['order:list'])]
    private string $billingAddress;
    #[Column(type: 'string')]
    #[Groups(['order:list'])]
    private string $shippingAddress;
    #[Column(type: 'datetime')]
    private \DateTime $createdAt;
    #[Column(type: 'string', nullable: true)]
    private ?string $paymentUrl;
    #[OneToOne(targetEntity: Cart::class, mappedBy: 'order')]
    #[Groups(['order:list'])]
    private Cart $cart;
    #[ManyToOne(targetEntity: User::class)]
    private ?User $user;
    #[ManyToOne(targetEntity: Guest::class)]
    private ?Guest $guest;
    #[OneToOne(targetEntity: OrderTracking::class, mappedBy: 'order')]
    private ?OrderTracking $orderTracking = null;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): void
    {
        $this->paymentUrl = $paymentUrl;
    }

    public function getOrderTracking(): ?OrderTracking
    {
        return $this->orderTracking;
    }

    public function setOrderTracking(?OrderTracking $orderTracking): void
    {
        $this->orderTracking = $orderTracking;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(?Guest $guest): void
    {
        $this->guest = $guest;
    }
}
