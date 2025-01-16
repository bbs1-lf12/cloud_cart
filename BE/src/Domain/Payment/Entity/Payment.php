<?php

declare(strict_types=1);

namespace App\Domain\Payment\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Order\Entity\Order;
use App\Domain\Payment\Repository\PaymentRepository;
use App\Domain\User\Entity\Guest;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity(repositoryClass: PaymentRepository::class)]
class Payment extends AbstractEntity
{
    #[Column(type: 'string')]
    private string $paymentId;
    #[Column(type: 'integer')]
    private int $amount;
    #[Column(type: 'string')]
    private string $currency;
    #[Column(type: 'string')]
    private string $status;
    #[oneToOne(targetEntity: Order::class)]
    private Order $order;
    #[ManyToOne(targetEntity: User::class)]
    private ?User $user;
    #[ManyToOne(targetEntity: Guest::class)]
    private ?Guest $guest;

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function setPaymentId(string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
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
