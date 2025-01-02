<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleFOService
{
    public function __construct(
        private readonly ArticleQueryBuilderService $articleQueryBuilderService,
        private readonly PaginatorService $paginator,
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
}
