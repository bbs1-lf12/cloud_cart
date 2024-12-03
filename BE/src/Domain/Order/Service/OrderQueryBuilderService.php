<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Domain\Order\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

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
}
