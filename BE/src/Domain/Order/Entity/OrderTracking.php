<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Order\Repository\OrderTrackingRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OrderTrackingRepository::class)]
#[Table(name: 'cart_order_tracking')]
class OrderTracking extends AbstractEntity
{
    #[OneToOne(targetEntity: Order::class, inversedBy: 'orderTracking')]
    private Order $order;
    #[Column(type: 'string')]
    private string $trackingId;

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    public function setTrackingId(string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }
}
