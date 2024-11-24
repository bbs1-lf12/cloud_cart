<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Order\Service\OrderAPIService;
use App\Domain\Payment\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
#[IsGranted('ROLE_USER')]
class OrderAPIController extends AbstractController
{
    public function __construct(
        private readonly OrderAPIService $orderService,
        private readonly SerializerInterface $serializer,
        private readonly PaypalService $paypalService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     * @throws \Exception
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

        $redirectUrl = $this->paypalService
            ->purchaseOrder(
                $order,
                $this->generateUrl(
                    'api_v1_payment_success',
                    [
                        'userId' => $this->getUser()->getId(),
                        'orderId' => $order->getId(),
                    ],
                    0,
                ),
                $this->generateUrl(
                    'api_v1_payment_cancel',
                    [],
                    0,
                ),
            )
        ;

        return new JsonResponse(
            data: [
                'paypal_url' => $redirectUrl,
            ],
        );
    }

    #[Route('/order', name: 'api_v1_list_orders', methods: ['GET'])]
    public function listOrders(): Response
    {
        $orders = $this->orderService
            ->listOrders()
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $orders,
                    'json',
                    ['groups' => 'order:list'],
                ),
        );
    }
}
