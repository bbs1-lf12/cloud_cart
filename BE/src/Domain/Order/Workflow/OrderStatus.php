<?php

declare(strict_types=1);

namespace App\Domain\Order\Workflow;

class OrderStatus
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';
    public const SHIPPED = 'shipped';
    public const DELIVERED = 'delivered';
    public const RETURNED = 'returned';
}
