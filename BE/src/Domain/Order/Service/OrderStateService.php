<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Entity\OrderTracking;
use App\Domain\Order\Exceptions\OrderStatusException;
use App\Domain\Order\Workflow\OrderTransitions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class OrderStateService
{
    public function __construct(
        private readonly WorkflowInterface $ordersStateMachine,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    public function assignConfirm(
        Order $order,
    ): void {
        $this->assignStatus(
            $order,
            OrderTransitions::TO_CONFIRMED,
        );
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    public function assignCancel(Order $order): void
    {
        $this->assignStatus(
            $order,
            OrderTransitions::TO_CANCELLED,
        );
    }

    public function canCancel(Order $order): bool
    {
        return $this->ordersStateMachine->can(
            $order,
            OrderTransitions::TO_CANCELLED,
        );
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    public function assignShip(
        Order $order,
        string $trackingId,
    ): void {
        $orderTracking = new OrderTracking();
        $orderTracking->setTrackingId($trackingId);
        $orderTracking->setOrder($order);
        $order->setOrderTracking($orderTracking);

        $this->entityManager
            ->persist($orderTracking)
        ;
        $this->entityManager
            ->flush()
        ;

        $this->assignStatus(
            $order,
            OrderTransitions::TO_SHIPPED,
        );
    }

    public function canShip(Order $order): bool
    {
        return $this->ordersStateMachine->can(
            $order,
            OrderTransitions::TO_SHIPPED,
        );
    }

    public function assignDelivered(Order $order): void
    {
        $this->assignStatus(
            $order,
            OrderTransitions::TO_DELIVERED,
        );
    }

    public function canDelivered(Order $order): bool
    {
        return $this->ordersStateMachine->can(
            $order,
            OrderTransitions::TO_DELIVERED,
        );
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    private function assignStatus(
        Order $order,
        string $transitionName,
    ): void {
        if (!$this->ordersStateMachine->can(
            $order,
            $transitionName,
        )) {
            throw new OrderStatusException(
                'Cannot execute transition ' . $transitionName . ' for the order ' . $order->getId(),
            );
        }

        $this->ordersStateMachine->apply(
            $order,
            $transitionName,
        );

        $this->entityManager
            ->flush()
        ;
    }

    /**
     * Refresh the order status, when the entity is created
     *
     * @param $order
     *
     * @return void
     */
    public function refreshOrderStatus($order): void
    {
        $this->ordersStateMachine->can(
            $order,
            '',
        );
    }
}
