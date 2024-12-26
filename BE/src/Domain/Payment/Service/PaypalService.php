<?php

declare(strict_types=1);

namespace App\Domain\Payment\Service;

use App\Domain\Mail\Listener\Event\ConfirmOrderPaymentMailEvent;
use App\Domain\Order\Entity\Order;
use App\Domain\Order\Service\OrderStateService;
use App\Domain\Payment\Entity\Payment;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PaypalService
{
    private GatewayInterface $gateway;
    private string $currency;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderStateService $orderStateService,
        private readonly RouterInterface $router,
        private readonly Security $security,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($_ENV['PAYPAL_CLIENT_ID']);
        $this->gateway->setSecret($_ENV['PAYPAL_SECRET_KEY']);
        $this->gateway->setTestMode(true);
        $this->currency = $_ENV['PAYMENT_CURRENCY'] ?? 'EUR';
    }

    /**
     * @throws \Exception
     */
    public function purchaseOrder(
        Order $order,
    ): string {
        $response = $this->gateway->purchase(
            [
                'amount' => ((float) $order->getTotalPrice() / 100),
                'currency' => $this->currency,
                'description' => 'Payment for order: ' . $order->getId(),
                'returnUrl' => $this->router->generate(
                    'api_v1_payment_success',
                    [
                        'userId' => $this->security
                            ->getUser()
                            ->getId(),
                        'orderId' => $order->getId(),
                    ],
                    0,
                ),
                'cancelUrl' => $this->router->generate(
                    'api_v1_payment_cancel',
                    [],
                    0,
                ),
            ],
        )
            ->send()
        ;

        try {
            if ($response->isRedirect()) {
                return $response->getRedirectUrl();
            } else {
                dd($response->getMessage());
            }
        } catch (\Exception $e) {
            throw new \Exception('Error on PayPal: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function completePurchase(
        Request $request,
        int $userId,
        int $orderId,
    ): ?Payment {
        $payerId = $request->query->get('PayerID');
        $transactionReference = $request->query->get('paymentId');

        if ($payerId && $transactionReference) {
            $response = $this->gateway
                ->completePurchase(
                    [
                        'payer_id' => $payerId,
                        'transactionReference' => $transactionReference,
                    ],
                )
                ->send()
            ;

            if ($response->isSuccessful()) {
                $user = $this->entityManager
                    ->getRepository(User::class)
                    ->find($userId)
                ;
                $order = $this->entityManager
                    ->getRepository(Order::class)
                    ->find($orderId)
                ;

                $data = $response->getData();
                $payment = new Payment();
                $payment->setPaymentId($data['id']);
                $payment->setAmount((int) floatval($data['transactions'][0]['amount']['total']));
                $payment->setCurrency($this->currency);
                $payment->setStatus($data['state']);
                $payment->setUser($user);
                $payment->setOrder($order);

                $this->orderStateService
                    ->assignConfirm($order)
                ;

                $this->entityManager
                    ->persist($payment)
                ;
                $this->entityManager
                    ->flush()
                ;

                $event = new ConfirmOrderPaymentMailEvent($user);
                $this->eventDispatcher
                    ->dispatch($event)
                ;

                return $payment;
            } else {
                throw new \Exception('Payment failed: ' . $response->getMessage());
            }
        } else {
            throw new \Exception('Payment failed: missing PayerID or paymentId');
        }
    }
}
