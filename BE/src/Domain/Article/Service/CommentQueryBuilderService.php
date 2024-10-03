<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Domain\Article\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class CommentQueryBuilderService
{
    public const ALIAS = 'c';

    public function __construct(
        readonly private EntityManagerInterface $entityManager,
    ) {
    }

    public function selectAllCommentsQB(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(Comment::class)
            ->createQueryBuilder(self::ALIAS);
    }

    public function byArticleId(
        QueryBuilder $qb,
        int $articleId
    ): QueryBuilder {
        $qb->innerJoin(self::ALIAS . '.article', 'a')
            ->andWhere('a.id = :articleId')
            ->setParameter('articleId', $articleId);

        return $qb;
    }
}
