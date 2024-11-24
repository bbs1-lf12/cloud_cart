<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener\Event;

use App\Domain\Cart\Entity\CartItem;
use Symfony\Contracts\EventDispatcher\Event;

class ReorderCartCartItemsPositionsEvent extends Event
{
    public function __construct(
        private readonly CartItem $cartItem,
        private readonly float $toPosition,
        private readonly bool $isDelete = false,
    ) {
    }

    public function getCartItem(): CartItem
    {
        return $this->cartItem;
    }

    public function getToPosition(): float
    {
        return $this->toPosition;
    }

    public function isDelete(): bool
    {
        return $this->isDelete;
    }
}
