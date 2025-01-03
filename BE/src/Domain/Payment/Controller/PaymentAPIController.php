<?php

declare(strict_types=1);

namespace App\Domain\Payment\Controller;

use App\Domain\Payment\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class PaymentAPIController extends AbstractController
{
    public function __construct(
        private readonly PaypalService $paypalService,
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/payment/success/{userId}/{orderId}', name: 'api_v1_payment_success', methods: ['GET'])]
    public function paymentSuccess(
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

    #[Route('/payment/error', name: 'api_v1_payment_cancel', methods: ['GET'])]
    public function paymentError()
    {
        dd('Payment error');
    }
}
