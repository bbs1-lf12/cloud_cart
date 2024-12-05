<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener\Event;

use App\Domain\User\Entity\User;

class ConfirmOrderPaymentMailEvent
{
    public function __construct(
        private readonly User $targetUser,
    ) {
    }

    public function getTargetUser(): User
    {
        return $this->targetUser;
    }
}
