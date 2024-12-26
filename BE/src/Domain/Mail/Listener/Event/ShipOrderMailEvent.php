<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener\Event;

use App\Domain\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ShipOrderMailEvent extends Event
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
