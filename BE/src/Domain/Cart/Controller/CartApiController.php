<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
#[IsGranted('ROLE_USER')]
class CartApiController extends AbstractController
{
    public function __construct(
        private readonly CartAPIService $cartService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/cart', name: 'api_v1_show_cart', methods: ['GET'])]
    public function showCart(Request $request): JsonResponse
    {
        $page = $this->cartService
            ->getCartPage($request)
        ;

        return new JsonResponse(
            $this->serializer
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
    #[Route('/cart/item', name: 'api_v1_add_item_to_cart', methods: ['POST'])]
    public function addItemToCart(
        Request $request,
    ): JsonResponse {
        $cartItem = $this->cartService
            ->addItemToCartId($request)
        ;

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $cartItem,
                    'json',
                    ['groups' => 'cart:list'],
                ),
            201,
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/cart/item/{cartItemId}', name: 'api_v1_edit_cart_item', methods: ['PUT'])]
    public function editCartItem(
        Request $request,
    ): JsonResponse {
        $cartItem = $this->cartService
            ->editCartItem($request);

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $cartItem,
                    'json',
                    ['groups' => 'cart:list'],
                ),
        );
    }
}
