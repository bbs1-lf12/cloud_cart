<?php

declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Cart\Repository\CartRepository;
use App\Domain\User\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: CartRepository::class)]
class Cart extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class)]
    private User $user;
    #[OneToMany(targetEntity: CartItem::class, mappedBy: 'cart')]
    private Collection $cartItems;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function setCartItems(Collection $cartItems): void
    {
        $this->cartItems = $cartItems;
    }

    public function addCartITem(CartItem $cartItem): void
    {
        $this->cartItems[] = $cartItem;
    }

    public function deleteCartItem(CartItem $cartItem): void
    {
        foreach ($this->cartItems as $key => $item) {
            if ($item->getId() === $cartItem->getId()) {
                unset($this->cartItems[$key]);
            }
        }
    }
}
