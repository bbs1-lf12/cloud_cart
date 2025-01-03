<?php

declare(strict_types=1);

namespace App\Domain\Payment\Controller;

use App\Domain\Payment\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaypalService $paypalService,
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/v1/payment/success/{userId}/{orderId}', name: 'api_v1_payment_success', methods: ['GET'])]
    public function paymentAPISuccess(
        int $userId,
        int $orderId,
        Request $request,
    ): JsonResponse {
        $this->paypalService
            ->completePurchase(
                $request,
                $userId,
                $orderId,
            )
        ;

        return new JsonResponse(
            [
                'message' => 'Order confirmed',
            ],
            Response::HTTP_OK,
        );
    }

    #[Route('/api/v1/payment/error', name: 'api_v1_payment_cancel', methods: ['GET'])]
    public function paymentAPIError(): JsonResponse
    {
        dd('Payment API error');

        return new JsonResponse(
            [
                'message' => 'Payment API error',
            ],
            Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/payment/success/{userId}/{orderId}', name: 'payment_fo_success', methods: ['GET'])]
    public function paymentFOSuccess(
        int $userId,
        int $orderId,
        Request $request,
    ): Response {
        $this->paypalService
            ->completePurchase(
                $request,
                $userId,
                $orderId,
            )
        ;

        $this->addFlash(
            'success',
            'Order confirmed',
        );
        return $this->redirectToRoute('article_list');
    }

    #[Route('/payment/error', name: 'payment_fo_error', methods: ['GET'])]
    public function paymentFOError(): Response
    {
        dd('Payment FO error');
        
        return $this->redirectToRoute('article_list');
    }
}
