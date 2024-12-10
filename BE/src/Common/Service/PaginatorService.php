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
        private readonly PaginatorInterface $paginator,
    ) {
    }

    public function getPagination(
        QueryBuilder $qb,
        Request $request,
    ): PaginationInterface {
        $page = intval($request->get('page') ?? 1);

        return $this->paginator
            ->paginate(
                $qb,
                $page,
                intval(
                    $_ENV['PAGINATOR_ITEMS_PER_PAGE'] ?? 10,
                ),
            )
        ;
    }

    public function getApiPagination(
        QueryBuilder $qb,
        Request $request,
    ): PaginationInterface {
        $payload = $request->getPayload()
            ->all()
        ;

        $page = intval($payload['page'] ?? 1);
        $itemsPerPage = intval(
            $payload['itemsPerPage']
            ?? intval(
                $_ENV['PAGINATOR_ITEMS_PER_PAGE']
            ?? 10,
            ),
        );

        return $this->paginator
            ->paginate(
                $qb,
                $page,
                $itemsPerPage,
            )
        ;
    }
}
