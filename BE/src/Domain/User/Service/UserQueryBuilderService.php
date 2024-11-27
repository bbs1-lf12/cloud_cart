<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class UserQueryBuilderService
{
    public const ALIAS = 'u';

    public function __construct(
        readonly private EntityManagerInterface $entityManager,
    ) {
    }

    public function selectAllUsersQB(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder(self::ALIAS)
            ->orderBy(
                self::ALIAS . '.id',
                'ASC',
            )
        ;
    }

    public function addFilters(
        QueryBuilder $qb,
        Request $request,
    ): QueryBuilder {
        $filter = $request->get('user_filter') ?? false;

        if (!$filter) {
            return $qb;
        }

        $search = $filter['search'] ?? false;
        $roles = $filter['roles'] ?? false;
        $isActive = $filter['isActive'] ?? false;

        if ($search) {
            $qb->andWhere('LOWER(u.email) LIKE LOWER(:search)')
                ->setParameter(
                    'search',
                    "%$search%",
                )
            ;
        }

        if ($roles) {
            $qb->andWhere('CONTAINS(u.roles, :roles) = true')
                ->setParameter(
                    'roles',
                    json_encode($roles),
                )
            ;
        }

        if ($isActive) {
            $qb->andWhere('u.isActive = :isActive')
                ->setParameter(
                    'isActive',
                    $isActive,
                )
            ;
        }

        return $qb;
    }
}
