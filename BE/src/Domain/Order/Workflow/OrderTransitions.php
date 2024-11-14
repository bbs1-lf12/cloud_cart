<?php

declare(strict_types=1);

namespace App\Domain\Order\Workflow;

class OrderTransitions
{
    public const TO_CONFIRMED = 'to_confirmed';
    public const TO_CANCELLED = 'to_cancelled';
    public const TO_SHIPPED = 'to_shipped';
    public const TO_DELIVERED = 'to_delivered';
    public const TO_RETURNED = 'to_returned';
}
