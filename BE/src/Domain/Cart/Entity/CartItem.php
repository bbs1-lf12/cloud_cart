<?php

declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Repository\CartItemRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Attribute\Groups;

#[Entity(repositoryClass: CartItemRepository::class)]
class CartItem extends AbstractEntity
{
    #[Column(type: 'float')]
    #[Groups(['cart:list'])]
    private float $position;
    #[Column(type: 'integer')]
    #[Groups(['cart:list'])]
    private int $quantity;
    #[ManyToOne(targetEntity: Article::class)]
    #[Groups(['cart:list'])]
    private Article $article;
    #[ManyToOne(targetEntity: Cart::class)]
    private Cart $cart;

    public function getPosition(): float
    {
        return $this->position;
    }

    public function setPosition(float $position): void
    {
        $this->position = $position;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }
}
