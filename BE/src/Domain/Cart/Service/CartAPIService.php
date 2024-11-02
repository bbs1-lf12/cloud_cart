<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Common\Service\PaginatorService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class CartAPIService
{
    public function __construct(
        private readonly CartEntityQueryBuilderService $cartQueryBuilderService,
        private readonly PaginatorService $paginator,
        private readonly Security $security
    ) {
    }

    public function getCartPage(Request $request): PaginationInterface
    {
        $user = $this->security
            ->getUser();

        $qb = $this->cartQueryBuilderService
            ->getCartByUserQB($user);

        return $this->paginator->getApiPagination(
            $qb,
            $request
        );
    }
}
