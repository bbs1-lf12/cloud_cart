<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderService
{
    public function __construct(
        private readonly PaginatorService $paginatorService,
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
    ) {
    }

    public function getAllOrdersPage(Request $request,
    ): PaginationInterface {
        $qb = $this->orderQueryBuilderService
            ->getOrdersQueryBuilder()
        ;

        return $this->paginatorService
            ->getPagination(
                $qb,
                $request,
            )
        ;
    }
}
