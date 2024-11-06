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
        $movingCartItem = $event->getCartItem();
        $cart = $this->entityManager
            ->getRepository(Cart::class)
            ->find(
                $movingCartItem
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

        $fromPosition = $movingCartItem->getPosition();
        $toPosition = $event->getToPosition();
        $targetCartItem = null;

        foreach ($cartItems as $cartItem) {
            if ($cartItem->getPosition() === $toPosition) {
                $targetCartItem = $cartItem;
                break;
            }
        }

        // reorder not needed
        if ($fromPosition === $toPosition) {
            return;
        }

        if ($fromPosition < $toPosition) {
            $movingCartItem->setPosition(
                $targetCartItem->getPosition() + 0.5,
            );
        } else {
            $movingCartItem->setPosition(
                $targetCartItem->getPosition() - 0.5,
            );
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
