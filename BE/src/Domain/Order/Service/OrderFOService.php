<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Service\PaginatorService;
use App\Domain\User\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderFOService
{
    public function __construct(
        private readonly Security $security,
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
        private readonly PaginatorService $paginator,
    ) {
    }

    public function getOrdersByUser(Request $request): PaginationInterface
    {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;

        $qb = $this->orderQueryBuilderService
            ->getOrdersByUser($user)
        ;
        $qb = $this->orderQueryBuilderService
            ->addFilters(
                $qb,
                $request,
                true,
            )
        ;

        return $this->paginator->getPagination(
            $qb,
            $request,
        );
    }
}
