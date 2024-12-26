<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener\Event;

use App\Domain\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class NewUserMailEvent extends Event
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    /**
     * @return \App\Domain\User\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
