<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleService
{
    public function __construct(
        private readonly PaginatorService $paginator,
        private readonly ArticleQueryBuilderService $articleQueryBuilderService,
    ) {
    }

    public function listAllPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->articleQueryBuilderService
            ->selectAllArticlesQB()
        ;
        $qb = $this->articleQueryBuilderService
            ->addFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginator
            ->getApiPagination(
                $qb,
                $request,
            )
        ;
    }
}
