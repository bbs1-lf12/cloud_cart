<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use App\Domain\Order\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderFOController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[Route('/order', name: 'place_order', methods: ['POST'])]
    public function placeOrder(Request $request): Response
    {
        try {
            $order = $this->orderService
                ->placeOrder()
            ;

            $event = new ReminderPayPalUrlMailEvent(
                $order->getUser(),
                $order,
            );
            $this->eventDispatcher
                ->dispatch($event)
            ;

            return $this->redirect($order->getPaymentUrl());
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $e->getMessage(),
            );
            $this->redirectToRoute('article_list');
        }
    }
}
