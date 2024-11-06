<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Order\Service\OrderAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
#[IsGranted('ROLE_USER')]
class OrderAPIController extends AbstractController
{
    public function __construct(
        private readonly OrderAPIService $orderService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
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
