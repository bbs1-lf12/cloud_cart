<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener\Event;

class GetCartItemPositionEvent
{
    private int $position = 0;

    public function __construct(
        private readonly int $cartId,
    ) {
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
