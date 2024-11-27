<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Mail\Listener\Event\NewUserMailEvent;
use App\Domain\User\Entity\User;
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
}
