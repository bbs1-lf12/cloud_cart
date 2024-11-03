<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener\Event;

use App\Domain\Cart\Entity\CartItem;
use Symfony\Contracts\EventDispatcher\Event;

class ReorderCartCartItemsPositionsEvent extends Event
{
    public function __construct(
        private readonly CartItem $cartItem,
    ) {
    }

    public function getCartItem(): CartItem
    {
        return $this->cartItem;
    }
}
