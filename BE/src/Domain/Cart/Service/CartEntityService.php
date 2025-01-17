<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

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
                ->flush()
            ;
        }
        return $cart;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function createGuestCart(
        Request $request,
    ): Cart {
        $payload = $request->getPayload()->all();
        $guestCart = $payload['guest_cart'];

        if (count($guestCart) <= 0) {
            throw new ApiException(
                'Guest cart is empty',
                400,
            );
        }

        $cart = new Cart();
        $position = 0;
        foreach ($guestCart as $item) {
            $product = $this->entityManager
                ->getRepository(Article::class)
                ->find($item['product_id'])
            ;
            if (empty($product)) {
                throw new ApiException(
                    'Product not found',
                    400,
                );
            }

            $cart_item = new CartItem();
            $cart_item->setQuantity($item['quantity']);
            $cart_item->setPosition($position);
            $cart_item->setArticle($product);
            $cart_item->setCart($cart);
            $cart->addCartItem($cart_item);

            $position++;
            $this->entityManager
                ->persist($cart_item);
        }

        return $cart;
    }
}
