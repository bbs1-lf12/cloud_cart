<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Cart\Service\CartEntityService;
use App\Domain\Order\Entity\Order;
use App\Domain\Payment\Service\PaypalService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderService
{
    public function __construct(
        private readonly PaginatorService $paginatorService,
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly OrderStateService $orderStateService,
        private readonly PaypalService $paypalService,
        private readonly CartEntityService $cartEntityService,
    ) {
    }

    public function getAllOrdersPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->orderQueryBuilderService
            ->getOrdersQueryBuilder()
        ;

        $qb = $this->orderQueryBuilderService
            ->addFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginatorService
            ->getPagination(
                $qb,
                $request,
            )
        ;
    }

    /**
     * @throws \Exception
     */
    public function getOrderById(int $id): Order
    {
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->find($id)
        ;

        if (!$order) {
            throw new \Exception('Order not found');
        }

        return $order;
    }

    /**
     * @throws \Exception
     */
    public function placeOrder(): Order
    {
        /** @var \App\Domain\User\Entity\User $currentUser */
        $currentUser = $this->security
            ->getUser()
        ;
        $cart = $this->cartEntityService
            ->getCurrentCart()
        ;

        if ($cart->getCartItems()
                ->count() === 0) {
            throw new \Exception(
                'Cannot place an order with an empty cart',
                400,
            );
        }

        $order = new Order();
        $order->setUser($currentUser);
        $order->setCart($cart);
        $order->setCreatedAt(new \DateTime());
        $cart->setOrder($order);

        // refresh the workflow to get the init state
        $this->orderStateService
            ->refreshOrderStatus($order)
        ;

        // TODO-JMP: hardcoded, needs to be real data
        $order->setShippingAddress('123 Main St, Springfield, IL 62701');
        $order->setBillingAddress('123 Main St, Springfield, IL 62701');

        $orderTotal = 0;
        /** @var \App\Domain\Cart\Entity\CartItem $ci */
        foreach ($cart->getCartItems() as $ci) {
            $orderTotal += $ci->getQuantity() * $ci->getArticle()
                    ->getPriceInCents()
            ;
        }
        $order->setTotalPrice($orderTotal);

        $this->entityManager
            ->persist($order)
        ;
        $this->entityManager
            ->flush()
        ;

        // after total price is set
        $order->setPaymentUrl(
            $this->paypalService
                ->purchaseOrder(
                    $order,
                ),
        );

        $this->entityManager
            ->flush()
        ;

        return $order;
    }
}
