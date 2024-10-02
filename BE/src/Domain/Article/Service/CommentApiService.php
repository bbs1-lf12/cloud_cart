<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentApiService
{
    public function __construct(
        private readonly CommentQueryBuilderService $commentQueryBuilderService,
        private readonly PaginatorService $paginator,
    )
    {
    }

    public function listAllCommentsByArticleId(
        Request $request,
        int $articleId
    ): PaginationInterface {
        $qb = $this->commentQueryBuilderService
            ->selectAllCommentsQB();
        $qb = $this->commentQueryBuilderService
            ->byArticleId(
                $qb,
                $articleId
            );

        return $this->paginator
            ->getApiPagination(
                $qb,
                $request
            );
    }
}
