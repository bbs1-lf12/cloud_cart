<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
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
            $qb->leftJoin(
                'o.user',
                'u',
                Join::WITH,
                'o.user = u.id',
            )
                ->andWhere("u.email LIKE :search")
                ->setParameter(
                    'search',
                    '%' . $search . '%',
                )
            ;
        }

        if ($status) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $status)
            ;
        }

        if ($formDate) {
            $qb->andWhere('o.createdAt >= :fromDate')
                ->setParameter('fromDate', $formDate)
            ;
        }

        if ($toDate) {
            $qb->andWhere('o.createdAt <= :toDate')
                ->setParameter('toDate', $toDate)
            ;
        }

        return $qb;
    }
}
