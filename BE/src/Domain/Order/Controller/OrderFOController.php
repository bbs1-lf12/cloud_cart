<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use App\Domain\Order\Entity\Order;
use App\Domain\Order\Form\OrdersFilterType;
use App\Domain\Order\Service\OrderFOService;
use App\Domain\Order\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderFOController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly OrderFOService $orderFOService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[Route('/order', name: 'list_orders', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function listOrders(Request $request): Response
    {
        $page = $this->orderFOService
            ->getOrdersByUser($request)
        ;

        $form = $this->createForm(OrdersFilterType::class);
        $form->handleRequest($request);

        return $this->render(
            'order/list.html.twig',
            [
                'orders' => $page->getItems(),
                'pager' => $page,
                'filterForm' => $form->createView(),
            ],
        );
    }

    #[Route('/order/{id}', name: 'show_order', methods: ['GET'])]
    public function showOrder(Request $request, int $id): Response
    {
        try {
            /** @var Order $order */
            $order = $this->orderFOService
                ->getOrderById($id)
            ;

            return $this->render(
                'order/show.html.twig',
                [
                    'order' => $order,
                ],
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $e->getMessage(),
            );
            return $this->redirectToRoute('list_orders');
        }
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
