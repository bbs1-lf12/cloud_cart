<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Domain\Cart\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartEntityService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    /**
     * Gets the current cart for the user, or creates a new one if none exists.
     *
     * @return \App\Domain\Cart\Entity\Cart
     */
    public function getCurrentCart(): Cart
    {
        /** @var \App\Domain\User\Entity\User $user */
        $user = $this->security
            ->getUser()
        ;

        $cart = $this->entityManager
            ->getRepository(Cart::class)
            ->getCurrentUserCart($user)
        ;

        if ($cart === null) {
            $cart = new Cart();
            $cart->setUser($user);

            $this->entityManager
                ->persist($cart)
            ;
            $this->entityManager
                ->flush();
        }
        return $cart;
    }
}
