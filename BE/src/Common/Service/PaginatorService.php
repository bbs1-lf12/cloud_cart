<?php

declare(strict_types=1);

namespace App\Common\Service;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginatorService
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function getApiPagination(
        QueryBuilder $qb,
        Request $request,
        int $limit = 10 // TODO: move default to config
    ): PaginationInterface {
        $payload = $request->getPayload()
            ->all();

        $page = intval($payload['page'] ?? 1);
        $itemsPerPage = intval($payload['itemsPerPage'] ?? $limit);

        return $this->paginator
            ->paginate(
                $qb,
                $page,
                $itemsPerPage
            );
    }
}
