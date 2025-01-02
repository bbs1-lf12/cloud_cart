<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleFOService
{
    public function __construct(
        private readonly ArticleQueryBuilderService $articleQueryBuilderService,
        private readonly PaginatorService $paginator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function listAllPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->articleQueryBuilderService
            ->selectAllArticlesQB()
        ;

        $qb = $this->articleQueryBuilderService
            ->addFOFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginator
            ->getPagination(
                $qb,
                $request,
            )
        ;
    }

    /**
     * @throws \Exception
     */
    public function getArticleById(
        int $id,
    ): ?Article {
        $article = $this->entityManager
            ->getRepository(Article::class)
            ->find($id);

        if (!$article) {
            throw new Exception('Article not found');
        }

        return $article;
    }
}
