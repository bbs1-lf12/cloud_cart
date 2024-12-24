<?php

declare(strict_types=1);

namespace App\Common\Service;

use App\Common\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractEntityQueryBuilderService
{
    protected const ALIAS = '';
    protected const ENTITY_FQN = AbstractEntity::class;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager
    ) {
    }

    protected function getEntityQueryBuilder(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(self::ENTITY_FQN)
            ->createQueryBuilder(static::ALIAS)
        ;
    }
}
