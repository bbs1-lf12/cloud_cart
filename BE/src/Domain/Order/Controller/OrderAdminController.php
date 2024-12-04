<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller;

use App\Domain\Order\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class OrderAdminController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
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
            ->getOrderById($id);

        return $this->render(
            'admin/order/show_order.html.twig',
            [
                'order' => $order,
            ],
        );
    }
}
