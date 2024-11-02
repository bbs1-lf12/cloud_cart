<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Common\Service\AbstractEntityQueryBuilderService;
use App\Domain\Cart\Entity\Cart;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class CartEntityQueryBuilderService extends AbstractEntityQueryBuilderService
{
    protected const ALIAS = 'c';
    protected const ENTITY_FQN = Cart::class;

    public function getCartByUserQB(User $user): QueryBuilder
    {
        return $this->getEntityQueryBuilder()
            ->where(static::ALIAS . '.user = :userId')
            ->setParameter(
                'userId',
                $user->getId(),
            )
            ->leftJoin(
                static::ALIAS . '.cartItems',
                'ci',
                Join::WITH,
                static::ALIAS . '.id = ci.cart'
            )
        ;
    }
}
