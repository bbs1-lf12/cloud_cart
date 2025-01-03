<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartFOService
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager,
        private readonly CartEntityQueryBuilderService $cartEntityQueryBuilderService,
    ) {
    }

    public function mergeCartSessionToUserCart(
        string $sessionId,
        User $user,
    ): void {
        $session = $this->requestStack
            ->getSession()
        ;
        $sessionCart = $session
            ->get(
                CartSessionService::CART_SESSION . $sessionId,
                [],
            )
        ;

        if (count($sessionCart) === 0) {
            return;
        }

        $articleRepository = $this->entityManager
            ->getRepository(Article::class)
        ;

        $cart = $this->getCart($user);
        /** @var array<int, CartItem> $cartItems */
        $cartItems = [];
        /** @var CartItem $item */
        foreach ($cart->getCartItems() as $item) {
            $cartItems[$item->getArticle()
                ->getId()] = $item;
        }

        $newItems = [];
        /** @var CartItem $item */
        foreach ($sessionCart as $articleId => $amount) {
            if (in_array(
                $articleId,
                array_keys($cartItems),
            )) {
                $cartItems[$articleId]->setQuantity(
                    $cartItems[$articleId]->getQuantity() + $amount,
                );
            } else {
                $newItem = new CartItem();
                $newItem->setCart($cart);
                $newItem->setArticle($articleRepository->find($articleId));
                $newItem->setQuantity($amount);
                $newItem->setPosition(0);
                $newItems[] = $newItem;
            }
        }

        $totalItems = count($cart->getCartItems());
        foreach ($newItems as $newItem) {
            $newItem->setPosition($totalItems++);
            $cart->addCartItem($newItem);
            $this->entityManager->persist($newItem);
        }

        $this->entityManager->flush();

        $session->remove(
            CartSessionService::CART_SESSION . $sessionId,
        );
    }

    public function getCart(User $user): Cart
    {
        $qb = $this->cartEntityQueryBuilderService
            ->getCartByUserQB($user)
        ;
        $cart = $qb->getQuery()
            ->getOneOrNullResult()
        ;

        if ($cart === null) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }
}