<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Exceptions\OrderStatusException;
use App\Domain\Order\Workflow\OrderStatus;
use Symfony\Component\Workflow\WorkflowInterface;

class OrderStateService
{
    public function __construct(
        private readonly WorkflowInterface $ordersStateMachine,
    ) {
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    public function assignPending(
        Order $order,
    ): void {
        $this->assignStatus(
            $order,
            OrderStatus::PENDING,
        );
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    public function assignConfirm(
        Order $order,
    ): void {
        $this->assignStatus(
            $order,
            OrderStatus::CONFIRMED,
        );
    }

    /**
     * @throws \App\Domain\Order\Exceptions\OrderStatusException
     */
    private function assignStatus(
        Order $order,
        string $status,
    ): void {
        if (!$this->ordersStateMachine->can(
            $order,
            $status,
        )) {
            throw new OrderStatusException(
                'Cannot assign status ' . $status . ' to order ' . $order->getId(),
            );
        }

        $this->ordersStateMachine->apply(
            $order,
            $status,
        );
    }
}
