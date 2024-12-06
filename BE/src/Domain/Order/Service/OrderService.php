<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Order\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderService
{
    public function __construct(
        private readonly PaginatorService $paginatorService,
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getAllOrdersPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->orderQueryBuilderService
            ->getOrdersQueryBuilder()
        ;

        $qb = $this->orderQueryBuilderService
            ->addFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginatorService
            ->getPagination(
                $qb,
                $request,
            )
        ;
    }

    /**
     * @throws \Exception
     */
    public function getOrderById(int $id): Order
    {
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->find($id)
        ;

        if (!$order) {
            throw new \Exception('Order not found');
        }

        return $order;
    }
}
