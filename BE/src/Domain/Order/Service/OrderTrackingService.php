<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Order\Entity\OrderTracking;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

class OrderTrackingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function getOrderByTrackingId(string $trackingId): OrderTracking
    {
        $orderTracking = $this->entityManager
            ->getRepository(OrderTracking::class)
            ->createQueryBuilder('ot')
            ->leftJoin(
                'ot.order',
                'o',
                Join::WITH,
                'ot.order = o',
            )
            ->andWhere('ot.trackingId = :tracking_id')
            ->setParameter(
                'tracking_id',
                $trackingId,
            )
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (!$orderTracking) {
            throw new ApiException(
                'No Order found by provided tracking id',
                404,
            );
        }

        return $orderTracking;
    }
}
