<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class OrderAPIController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/order', name: 'api_v1_place_order', methods: ['POST'])]
    public function placeOrder(
        Request $request,
    ): JsonResponse {
        $order = $this->orderService
            ->placeOrder(
                $request,
            )
        ;

        return new JsonResponse(
            data: $order,
        );
    }
}
