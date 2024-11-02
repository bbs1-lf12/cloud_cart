<?php

declare(strict_types=1);

namespace App\Common\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractEntityQueryBuilderService
{
    protected const ALIAS = '';
    protected const ENTITY_FQN = '';

    public function __construct(
        protected readonly EntityManagerInterface $entityManager
    )
    {
    }

    protected function getEntityQueryBuilder(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(static::ENTITY_FQN)
            ->createQueryBuilder(static::ALIAS)
        ;
    }
}
