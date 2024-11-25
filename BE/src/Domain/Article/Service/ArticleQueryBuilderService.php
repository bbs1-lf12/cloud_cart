<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Utils\PriceUtils;
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
            ->orderBy(self::ALIAS . '.id', 'ASC')
        ;
    }

    public function addFilters(
        QueryBuilder $qb,
        Request $request
    ): QueryBuilder {
        $payload = $request->getPayload()
            ->all();
        $query = $request->get('article_filter') ?? false;
        $filter = $payload['filter'] ?? $query ?? false;

        if (!$filter) {
            return $qb;
        }

        $search = $filter['search'] ?? false;
        $priceFrom = $filter['priceFrom'] ? PriceUtils::toCents($filter['priceFrom']) : false;
        $priceTo = $filter['priceTo'] ? PriceUtils::toCents($filter['priceTo']) : false;
        $available = $filter['available'] ?? false;
        $isFeatured = $filter['isFeatured'] ?? false;
        $minScore = $filter['minScore'] ?? false;
        $maxScore = $filter['maxScore'] ?? false;
        $categories = $filter['categories'] ?? false;

        if ($search) {
            $qb->andWhere('LOWER(a.title) LIKE LOWER(:search)')
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
