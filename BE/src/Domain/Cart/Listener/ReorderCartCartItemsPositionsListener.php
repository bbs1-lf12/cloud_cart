<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Listener\Event\ReorderCartCartItemsPositionsEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ReorderCartCartItemsPositionsListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function __invoke(ReorderCartCartItemsPositionsEvent $event): void
    {
        $cartItem = $event->getCartItem();
        $cart = $this->entityManager
            ->getRepository(Cart::class)
            ->find(
                $cartItem
                    ->getCart()
                    ->getId(),
            )
        ;

        if ($cart === null) {
            throw new ApiException(
                'Cart not found',
                404,
            );
        }

        $cartItems = $cart->getCartItems()
            ->toArray()
        ;
        $total = count($cartItems);
        if ($total === 0) {
            return;
        }

        // adding the modified position to the cart item
        foreach ($cartItems as $ci) {
            if ($ci->getId() === $cartItem->getId()) {
                $ci->setPosition($cartItem->getPosition());
            }
        }

        usort(
            $cartItems,
            function (
                $a,
                $b,
            ) {
                return $a->getPosition() <=> $b->getPosition();
            },
        );

        // recalculating the positions
        $position = 0;
        foreach ($cartItems as $ci) {
            $ci->setPosition($position);
            if ($ci->getId() === $cartItem->getId()) {
                $cartItem->setPosition($position);
            }
            $position++;
        }

        $cart->setCartItems(new ArrayCollection($cartItems));

        $this->entityManager
            ->persist($cart)
        ;
        $this->entityManager
            ->flush()
        ;
    }
}
