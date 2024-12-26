<?php

declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Cart\Repository\CartRepository;
use App\Domain\Order\Entity\Order;
use App\Domain\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Serializer\Attribute\Groups;

#[Entity(repositoryClass: CartRepository::class)]
class Cart extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class)]
    private User $user;
    #[OneToMany(targetEntity: CartItem::class, mappedBy: 'cart')]
    #[Groups(['cart:list'])]
    private Collection $cartItems;
    #[OneToOne(targetEntity: Order::class, inversedBy: 'cart')]
    private ?Order $order = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

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

    public function addCartItem(CartItem $cartItem): CartItem
    {
        $this->cartItems->add($cartItem);
        return $cartItem;
    }

    public function getCartItem(int $articleId): ?CartItem
    {
        /** @var \App\Domain\Cart\Entity\CartItem $item */
        foreach ($this->cartItems as $item) {
            if ($item->getArticle()
                    ->getId() === $articleId) {
                return $item;
            }
        }
        return null;
    }

    public function deleteCartItem(CartItem $cartItem): void
    {
        foreach ($this->cartItems as $key => $item) {
            if ($item->getId() === $cartItem->getId()) {
                unset($this->cartItems[$key]);
            }
        }
    }

    /**
     * If two cart items have the same article, they are considered equal.
     *
     * @param CartItem $cartItem
     *
     * @return bool
     */
    public function hasCartItem(CartItem $cartItem): bool
    {
        /** @var \App\Domain\Cart\Entity\CartItem $item */
        foreach ($this->cartItems as $item) {
            if ($item->getArticle()
                    ->getId() === $cartItem->getArticle()
                    ->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
}
