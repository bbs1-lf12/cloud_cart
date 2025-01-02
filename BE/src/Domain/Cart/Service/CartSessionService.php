<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CartSessionService
{
    public const CART_SESSION = 'cart_';

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function addArticle(Request $request): void
    {
        $articleId = $request->request
            ->get('article_id')
        ;
        $amount = $request->request
            ->get('article_amount', 1)
        ;
        $amount = intval($amount);

        $session = $this->requestStack
            ->getSession()
        ;
        $id = $session->getId();
        $cart = $session->get(
            static::CART_SESSION . $id,
            [],
        );

        if (isset($cart[$articleId])) {
            $cart[$articleId] += $amount;
        } else {
            $cart[$articleId] = $amount;
        }

        $session->set(
            static::CART_SESSION . $id,
            $cart,
        );
    }
}
