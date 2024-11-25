<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public function __construct(
        private readonly PaginatorService $paginator,
        private readonly UserQueryBuilderService $userQueryBuilderService,
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
}
