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

    public static function statuses(): array
    {
        return [
          self::PENDING => self::PENDING,
          self::CONFIRMED => self::CONFIRMED,
          self::CANCELLED => self::CANCELLED,
          self::SHIPPED => self::SHIPPED,
          self::DELIVERED => self::DELIVERED,
          self::RETURNED => self::RETURNED,
        ];
    }
}
