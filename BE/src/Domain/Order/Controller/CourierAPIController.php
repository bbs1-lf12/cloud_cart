<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Order\Service\OrderStateService;
use App\Domain\Order\Service\OrderTrackingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_COURIER')]
class CourierAPIController extends AbstractController
{
    public function __construct(
        private readonly OrderTrackingService $orderTrackingService,
        private readonly OrderStateService $orderStateService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/api/v1/courier/delivered/{trackingId}', name: 'api_v1_courier_order_delivered', methods: ['POST'])]
    public function orderDelivered(
        string $trackingId,
    ): JsonResponse {
        $order = $this->orderTrackingService
            ->getOrderByTrackingId($trackingId)
            ->getOrder()
        ;

        $canDelivered = $this->orderStateService
            ->canDelivered($order)
        ;
        if ($canDelivered) {
            $this->orderStateService
                ->assignDelivered($order)
            ;
        } else {
            throw new ApiException(
                'The Order cannot be set to deliver status',
                400,
            );
        }

        return new JsonResponse();
    }
}
