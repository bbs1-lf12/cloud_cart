<?php

declare(strict_types=1);

namespace App\Domain\Order\Twig;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Service\OrderStateService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OrderExtension extends AbstractExtension
{
    public function __construct(
        private readonly OrderStateService $orderStateService,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'canCancel',
                [
                    $this,
                    'canCancel',
                ],
            ),
            new TwigFunction(
                'canShip',
                [
                    $this,
                    'canShip',
                ],
            ),
            new TwigFunction(
                'isPending',
                [
                    $this,
                    'isPending',
                ],
            ),
        ];
    }

    public function canCancel(Order $order): bool
    {
        return $this->orderStateService
            ->canCancel($order)
        ;
    }

    public function canShip(Order $order): bool
    {
        return $this->orderStateService
            ->canShip($order)
        ;
    }

    public function isPending(Order $order): bool
    {
        return $order->getStatus() === OrderStatus::PENDING;
    }
}
