<?php

declare(strict_types=1);

namespace App\Domain\User\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class UserRegistrationAPIDTO
{
    #[SerializedName('username')]
    private string $email;
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
