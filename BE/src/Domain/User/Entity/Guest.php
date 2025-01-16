<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\User\Repository\GuestRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: GuestRepository::class)]
class Guest extends AbstractEntity
{
    #[Column(type: 'string')]
    private string $email;
    #[Column(type: 'string')]
    private string $billingAddress;
    #[Column(type: 'string')]
    private string $shippingAddress;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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
}
