<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
#[IsGranted('ROLE_USER')]
class CartApiController extends AbstractController
{
    public function __construct(
        private readonly CartAPIService $cartAPIService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/cart', name: 'api_v1_show_cart', methods: ['GET'])]
    public function showCart(Request $request): Response
    {
        $page = $this->cartAPIService
            ->getCartPage($request)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $page->getItems(),
                    'json',
                    ['groups' => 'cart:list'],
                ),
            headers: [
                'x-page' => $page->getCurrentPageNumber(),
                'x-total-pages' => $page->getPageCount(),
            ],
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/cart/item', name: 'api_v1_add_cart_item_to_cart', methods: ['POST'])]
    public function addCartItemToCart(
        Request $request,
    ): Response {
        $cartItem = $this->cartAPIService
            ->addCartItemToCart($request)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $cartItem,
                    'json',
                    ['groups' => 'cart:list'],
                ),
            status: 201,
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/cart/item/{cartItemId}', name: 'api_v1_edit_cart_item', methods: ['PUT'])]
    public function editCartItem(
        Request $request,
    ): Response {
        $cartItem = $this->cartAPIService
            ->editCartItem($request)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $cartItem,
                    'json',
                    ['groups' => 'cart:list'],
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/cart/item/{cartItemId}', name: 'api_v1_delete_cart_item', methods: ['DELETE'])]
    public function deleteCartItem(
        Request $request,
    ): Response {
        $cartItem = $this->cartAPIService
            ->deleteCartItem($request)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $cartItem,
                    'json',
                    ['groups' => 'cart:list'],
                ),
        );
    }
}
