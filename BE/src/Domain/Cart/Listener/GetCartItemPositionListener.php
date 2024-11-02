<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Listener\Event\GetCartItemPositionEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class GetCartItemPositionListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(GetCartItemPositionEvent $event): void
    {
        $amount = $this->entityManager
            ->getRepository(CartItem::class)
            ->createQueryBuilder('ci')
            ->where('ci.cart = :cartId')
            ->setParameter('cartId', $event->getCartId())
            ->getQuery()
            ->getArrayResult();
        $event->setPosition(count($amount));
    }
}
