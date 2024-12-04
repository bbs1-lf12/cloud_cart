<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Cart\Service\CartEntityService;
use App\Domain\Order\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

class OrderAPIService
{
    public function __construct(
        private readonly Security $security,
        private readonly CartEntityService $cartEntityService,
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderStateService $orderStateService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function placeOrder(
        Request $request,
    ): Order {
        /** @var \App\Domain\User\Entity\User $currentUser */
        $currentUser = $this->security
            ->getUser()
        ;
        $cart = $this->cartEntityService
            ->getCurrentCart()
        ;

        if ($cart->getCartItems()
                ->count() === 0) {
            throw new ApiException(
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

        $this->mapOrderFromPayload(
            $order,
            $request->getPayload(),
        );

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

        return $order;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function mapOrderFromPayload(
        Order $order,
        InputBag $payload,
    ): void {
        try {
            $order->setBillingAddress($payload->get('billing_address'));
            $order->setShippingAddress($payload->get('shipping_address'));
        } catch (\Throwable $e) {
            throw new ApiException(
                'Invalid payload',
                400,
            );
        }
    }

    public function listOrdersByUser(): array
    {
        $currentUser = $this->security
            ->getUser()
        ;

        return $this->entityManager
            ->getRepository(Order::class)
            ->findBy(
                ['user' => $currentUser],
            )
        ;
    }
}
