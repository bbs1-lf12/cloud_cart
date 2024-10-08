<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Domain\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class ArticleQueryBuilderService
{
    public const ALIAS = 'a';

    public function __construct(
        readonly private EntityManagerInterface $entityManager,
    ) {
    }

    public function selectAllArticlesQB(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(Article::class)
            ->createQueryBuilder(self::ALIAS)
        ;
    }

    public function addFilters(
        QueryBuilder $qb,
        Request $request
    ): QueryBuilder {
        $payload = $request->getPayload()
            ->all();
        $filter = $payload['filter'] ?? false;

        if (!$filter) {
            return $qb;
        }

        $search = $filter['search'] ?? false;
        $priceFrom = $filter['priceFrom'] ?? false;
        $priceTo = $filter['priceTo'] ?? false;
        $available = $filter['available'] ?? false;
        $isFeatured = $filter['isFeatured'] ?? false;
        $minScore = $filter['minScore'] ?? false;
        $maxScore = $filter['maxScore'] ?? false;
        $categories = $filter['categories'] ?? false;

        if ($search) {
            $qb->andWhere('a.title LIKE :search')
                ->setParameter('search', "%$search%");
        }

        if ($priceFrom) {
            $qb->andWhere('a.priceInCents >= :priceFrom')
                ->setParameter('priceFrom', $priceFrom);
        }

        if ($priceTo) {
            $qb->andWhere('a.priceInCents <= :priceTo')
                ->setParameter('priceTo', $priceTo);
        }

        if ($available) {
            $qb->andWhere('a.stock > 0');
        }

        if ($isFeatured) {
            $qb->andWhere('a.isFeatured = :isFeatured')
                ->setParameter('isFeatured', $isFeatured);
        }

        if ($minScore) {
            $qb->andWhere('a.score >= :minScore')
                ->setParameter('minScore', $minScore);
        }

        if ($maxScore) {
            $qb->andWhere('a.score <= :maxScore')
                ->setParameter('maxScore', $maxScore);
        }

        if ($categories) {
            $qb->join('a.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $categories);
        }

        return $qb;
    }
}
