<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Exceptions\OrderStatusException;
use App\Domain\Order\Workflow\OrderTransitions;
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
