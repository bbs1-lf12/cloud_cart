<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener\Event;

use App\Domain\Cart\Entity\Cart;
use Symfony\Contracts\EventDispatcher\Event;

class CreateCartEvent extends Event
{
    private Cart $newCart;

    public function __construct()
    {
    }

    public function getNewCart(): Cart
    {
        return $this->newCart;
    }

    public function setNewCart(Cart $newCart): void
    {
        $this->newCart = $newCart;
    }
}
