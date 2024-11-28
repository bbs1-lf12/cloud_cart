<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Mail\Listener\Event\NewUserMailEvent;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public function __construct(
        private readonly PaginatorService $paginator,
        private readonly UserQueryBuilderService $userQueryBuilderService,
        private readonly UserRegistrationService $userRegistrationService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function listAllPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->userQueryBuilderService
            ->selectAllUsersQB()
        ;
        $qb = $this->userQueryBuilderService
            ->addFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginator
            ->getPagination(
                $qb,
                $request,
            )
        ;
    }

    public function create(
        User $user,
    ): void {
        $user->setPassword(
            $user->getPassword(),
        );
        $this->userRegistrationService
            ->registerUser($user)
        ;

        $event = new NewUserMailEvent($user);
        $this->eventDispatcher
            ->dispatch($event)
        ;
    }

    /**
     * @throws \Exception
     */
    public function getUserById(
        int $id,
    ): User {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id)
        ;

        if ($user === null) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    public function update(
        User $user,
        string $oldPassword,
    ): void {
        // Password may be empty on the form
        if (empty($user->getPassword())) {
            $user->setPassword(
                $oldPassword,
            );
        }

        $this->entityManager
            ->persist($user)
        ;
        $this->entityManager
            ->flush()
        ;
    }
}
