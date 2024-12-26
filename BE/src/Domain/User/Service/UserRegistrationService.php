<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function registerUser(
        User $user,
    ): void {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $user->getPassword(),
            ),
        );

        $this->entityManager
            ->persist($user)
        ;
        $this->entityManager
            ->flush()
        ;
    }
}
