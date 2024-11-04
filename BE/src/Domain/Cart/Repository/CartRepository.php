<?php

declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Entity\Cart;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityRepository;

class CartRepository extends EntityRepository
{
    /**
     * Returns a cart for the current user, that has no order yet.
     * This is used to get the current cart for the user.
     *
     * @param \App\Domain\User\Entity\User $user
     *
     * @return \App\Domain\Cart\Entity\Cart|null
     */
    public function getCurrentUserCart(
        User $user,
    ): ?Cart {
        return $this->createQueryBuilder('c')
            ->where('c.order IS NULL')
            ->andWhere('c.user = :user')
            ->setParameter(
                'user',
                $user,
            )
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
