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
    private Collection $products;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): void
    {
        $this->products = $products;
    }

    public function addProduct(CartItem $product): void
    {
        $this->products[] = $product;
    }

    public function deleteProduct(CartItem $product): void
    {
        foreach ($this->products as $key => $item) {
            if ($item->getId() === $product->getId()) {
                unset($this->products[$key]);
            }
        }
    }
}
