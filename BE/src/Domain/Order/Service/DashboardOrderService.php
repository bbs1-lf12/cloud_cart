<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Utils\PriceUtils;
use App\Domain\Order\Workflow\OrderStatus;

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
            ->getSingleScalarResult() ?? 0;
    }

    public function getOrdersToDeliver(): ?array
    {
        return $this->orderQueryBuilderService
            ->getOrdersByStatus(
                OrderStatus::CONFIRMED,
            )
        ;
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    public function getRevenuePerDayDataSet(int $days): array
    {
        $label = [];
        $data = [];

        // per day retrieve data set and date
        for ($day = 0; $day < $days; $day++) {
            // get the date starting from today
            $date = (new \DateTime())
                ->setTime(
                    0,
                    0,
                    0,
                )
                ->modify("-{$day} days")
            ;

            $label[] = $date->format('Y-m-d');
            $data[] = PriceUtils::toPrice(
                $this->getRevenue(
                    from: $date,
                ),
            );
        }

        $label = array_reverse($label);
        $data = array_reverse($data);

        return [
            'label' => $label,
            'data' => $data,
        ];
    }
}
