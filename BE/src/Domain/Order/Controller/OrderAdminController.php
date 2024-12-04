<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use App\Domain\Order\Service\OrderService;
use App\Domain\Order\Service\OrderStateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class OrderAdminController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly OrderStateService $orderStateService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[Route('/admin/orders', name: 'admin_order_list')]
    public function list(Request $request): Response
    {
        $page = $this->orderService
            ->getAllOrdersPage($request)
        ;

        return $this->render(
            'admin/order/list_orders.html.twig',
            [
                'orders' => $page->getItems(),
                'page' => $page->getCurrentPageNumber(),
                'totalPages' => $page->getPageCount(),
                //                'filterForm' => $form->createView(),
            ],
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/admin/orders/{id}', name: 'admin_order_show')]
    public function show(
        int $id,
        Request $request,
    ): Response {
        $order = $this->orderService
            ->getOrderById($id)
        ;

        return $this->render(
            'admin/order/show_order.html.twig',
            [
                'order' => $order,
            ],
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/admin/orders/{id}/status/cancel', name: 'admin_order_status_cancel')]
    public function cancel(int $id): Response
    {
        $order = $this->orderService
            ->getOrderById($id)
        ;
        $canCanel = $this->orderStateService
            ->canCancel($order);

        if ($canCanel) {
            $this->orderStateService
                ->assignCancel($order);

            $this->addFlash(
                'success',
                'Order has been cancelled',
            );
        } else {
            $this->addFlash(
                'error',
                'Order cannot be cancelled',
            );
        }

        return $this->redirectToRoute(
            'admin_order_show',
            ['id' => $id],
        );
    }

    /**
     * @throws \Exception
     */
    #[Route('/admin/orders/{id}/reminder', name: 'admin_order_send_reminder_paypal_url')]
    public function sendReminderPayPalUrl(int $id): Response
    {
        $order = $this->orderService
            ->getOrderById($id)
        ;

        $event = new ReminderPayPalUrlMailEvent(
            $order->getUser(),
            $order,
        );
        $this->eventDispatcher
            ->dispatch($event);

        $this->addFlash(
            'success',
            'PayPal email reminder has been sent',
        );

        return $this->redirectToRoute(
            'admin_order_show',
            ['id' => $id],
        );
    }
}
