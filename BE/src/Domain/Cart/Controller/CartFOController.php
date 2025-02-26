<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartFOService;
use App\Domain\Cart\Service\CartSessionService;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartFOController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly CartSessionService $cartSessionService,
        private readonly CartFOService $cartFOService,
    ) {
    }

    #[Route('/cart', name: 'cart_show', methods: ['GET'])]
    public function showCart(): Response
    {
        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            return $this->render(
                'cart/sessionCart.html.twig',
            );
        }

        return $this->render(
            'cart/userCart.html.twig',
            [
                'cart' => $this->cartFOService
                    ->getCart($user),
            ],
        );
    }

    #[Route('/cart', name: 'cart_add', methods: ['POST'])]
    public function addToCart(
        Request $request,
    ): Response {
        $articleId = intval(
            $request->request
                ->get('article_id'),
        );
        $amount = intval(
            $request->request
                ->get(
                    'article_amount',
                    1,
                ),
        );

        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            $this->cartSessionService
                ->addArticle(
                    $articleId,
                    $amount,
                )
            ;
        } else {
            $this->cartFOService
                ->addArticle(
                    $user,
                    $articleId,
                    $amount,
                )
            ;
        }

        return $this->redirectToRoute('article_list');
    }

    #[Route('/cart/item/reduce', name: 'cartitem_reduce_quantity', methods: ['POST'])]
    public function reduceQuantity(Request $request): Response
    {
        $itemId = $request
            ->request
            ->get(
                'item_id',
                null,
            )
        ;

        if ($itemId === null) {
            return $this->redirectToRoute('cart_show');
        }
        $itemId = intval($itemId);

        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            $this->cartSessionService
                ->reduceQuantity($itemId)
            ;
        } else {
            $this->cartFOService
                ->reduceQuantity(
                    $user,
                    $itemId,
                )
            ;
        }

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/item/increase', name: 'cartitem_increase_quantity', methods: ['POST'])]
    public function increaseQuantity(Request $request): Response
    {
        $itemId = $request
            ->request
            ->get('item_id')
        ;

        if ($itemId === null) {
            return $this->redirectToRoute('cart_show');
        }
        $itemId = intval($itemId);

        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            $this->cartSessionService
                ->increaseQuantity($itemId)
            ;
        } else {
            $this->cartFOService
                ->increaseQuantity(
                    $user,
                    $itemId,
                )
            ;
        }

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/item/remove', name: 'cartitem_remove', methods: ['POST'])]
    public function removeFromCart(Request $request): Response
    {
        $itemId = $request
            ->request
            ->get('item_id')
        ;

        if ($itemId === null) {
            return $this->redirectToRoute('cart_show');
        }
        $itemId = intval($itemId);

        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            $this->cartSessionService
                ->removeItem($itemId)
            ;
        } else {
            $this->cartFOService
                ->removeItem(
                    $user,
                    $itemId,
                )
            ;
        }

        return $this->redirectToRoute('cart_show');
    }
}
