<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Workflow\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class OrderQueryBuilderService
{
    public const ALIAS = 'o';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getOrdersQueryBuilder(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(Order::class)
            ->createQueryBuilder(self::ALIAS)
        ;
    }

    public function addFilters(
        QueryBuilder $qb,
        Request $request,
    ): QueryBuilder {
        $filter = $request->get('orders_filter') ?? false;

        if (!$filter) {
            return $qb;
        }

        $search = $filter['search'] ?? false;
        $status = $filter['status'] ?? false;
        $formDate = $filter['fromDate'] ?? false;
        $toDate = $filter['toDate'] ?? false;

        if ($search) {
            $this->joinUsersTable($qb)
                ->andWhere("u.email LIKE :search")
                ->setParameter(
                    'search',
                    '%' . $search . '%',
                )
            ;
        }

        if ($status) {
            $qb->andWhere('o.status = :status')
                ->setParameter(
                    'status',
                    $status,
                )
            ;
        }

        if ($formDate) {
            $qb->andWhere('o.createdAt >= :fromDate')
                ->setParameter(
                    'fromDate',
                    $formDate,
                )
            ;
        }

        if ($toDate) {
            $qb->andWhere('o.createdAt <= :toDate')
                ->setParameter(
                    'toDate',
                    $toDate,
                )
            ;
        }

        return $qb;
    }

    public function joinUsersTable(QueryBuilder $qb): QueryBuilder
    {
        return $qb
            ->select(
                'o',
                'u',
            )
            ->leftJoin(
                'o.user',
                'u',
                Join::WITH,
                'o.user = u.id',
            )
        ;
    }

    public function getOrdersRevenueQueryBuilder(
        \DateTime $from = null,
        \DateTime $to = null,
    ): QueryBuilder {
        $qb = $this->getOrdersQueryBuilder()
            ->select('SUM(o.totalPrice)')
            ->andWhere('o.status in (:statuses)')
            ->setParameter(
                'statuses',
                [
                    OrderStatus::CONFIRMED,
                    OrderStatus::SHIPPED,
                    OrderStatus::DELIVERED,
                ],
            )
        ;

        if ($from) {
            $qb->andWhere('o.createdAt >= :from')
                ->setParameter(
                    'from',
                    $from,
                )
            ;
        }

        if ($to) {
            $qb->andWhere('o.createdAt <= :to')
                ->setParameter(
                    'to',
                    $to,
                )
            ;
        }

        return $qb;
    }

    public function getOrdersByStatus(string $status): array
    {
        if (!$status) {
            return [];
        }
        $qb = $this->getOrdersQueryBuilder()
            ->andWhere('o.status = :status')
            ->setParameter(
                'status',
                $status,
            )
            ->setMaxResults(5) // TODO-JMP: configurable?
        ;
        return $this->joinUsersTable($qb)
            ->getQuery()
            ->getResult()
        ;
    }
}
