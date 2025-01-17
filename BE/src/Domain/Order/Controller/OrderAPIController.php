<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use App\Domain\Order\Service\OrderAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class OrderAPIController extends AbstractController
{
    public function __construct(
        private readonly OrderAPIService $orderService,
        private readonly SerializerInterface $serializer,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/order/guest', name: 'api_v1_place_guest_order', methods: ['POST'])]
    public function placeGuestOrder(
        Request $request,
    ): JsonResponse {
        $order = $this->orderService
            ->placeGuestOrder(
                $request,
            )
        ;

        return new JsonResponse(
            data: [
                'paypal_url' => $order->getPaymentUrl(),
            ],
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     * @throws \Exception
     */
    #[Route('/order', name: 'api_v1_place_order', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function placeOrder(
        Request $request,
    ): JsonResponse {
        $order = $this->orderService
            ->placeOrder(
                $request,
            )
        ;

        $event = new ReminderPayPalUrlMailEvent(
            $order->getUser(),
            $order,
        );
        $this->eventDispatcher
            ->dispatch($event);

        return new JsonResponse(
            data: [
                'paypal_url' => $order->getPaymentUrl(),
            ],
        );
    }

    #[Route('/order', name: 'api_v1_list_orders', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function listOrders(): Response
    {
        $orders = $this->orderService
            ->listOrdersByUser()
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
