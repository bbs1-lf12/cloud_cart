<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Order\Entity\Order;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderFOService
{
    public function __construct(
        private readonly Security $security,
        private readonly OrderQueryBuilderService $orderQueryBuilderService,
        private readonly PaginatorService $paginator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getOrdersByUser(Request $request): PaginationInterface
    {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;

        $qb = $this->orderQueryBuilderService
            ->getOrdersByUser($user)
        ;
        $qb = $this->orderQueryBuilderService
            ->addFilters(
                $qb,
                $request,
                true,
            )
        ;

        return $this->paginator->getPagination(
            $qb,
            $request,
        );
    }

    /**
     * @throws \Exception
     */
    public function getOrderById(int $id)
    {
        $order = $this->entityManager
            ->getRepository(Order::class)
            ->find($id)
        ;

        if (
            !$order
            || $order->getUser() !== $this->security->getUser()
        ) {
            throw new \Exception('Order not found');
        }

        return $order;
    }
}
