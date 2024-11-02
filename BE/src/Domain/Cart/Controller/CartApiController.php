<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
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
            [
                "page" => $page->getCurrentPageNumber(),
                "totalPages" => $page->getPageCount(),
                "cart" => $this->serializer
                    ->serialize(
                        $page->getItems(),
                        'json',
                        ['groups' => 'cart:list'],
                    ),
            ],
        );
    }
}
