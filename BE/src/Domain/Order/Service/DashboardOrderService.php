<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

class DashboardOrderService
{
    public function __construct(
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
    ) {
    }

    public function getRevenue(
        \DateTime $from = null,
        \DateTime $to = null,
    ): int {
        return $this->orderQueryBuilderService
            ->getOrdersRevenueQueryBuilder(
                $from,
                $to,
            )
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
