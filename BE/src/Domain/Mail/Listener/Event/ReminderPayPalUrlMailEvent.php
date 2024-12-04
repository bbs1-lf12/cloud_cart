<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener\Event;

use App\Domain\Order\Entity\Order;
use App\Domain\User\Entity\User;

class ReminderPayPalUrlMailEvent
{
    public function __construct(
        private readonly User $targetUser,
        private readonly Order $order,
    ) {
    }

    public function getTargetUser(): User
    {
        return $this->targetUser;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
