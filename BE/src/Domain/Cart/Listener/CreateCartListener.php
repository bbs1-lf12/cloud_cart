<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Listener\Event\CreateCartEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CreateCartListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function __invoke(CreateCartEvent $event): void
    {
        $cart = new Cart();
        $cart->setUser(
            $this->security
                ->getUser(),
        );
        $this->entityManager
            ->persist($cart)
        ;
        $this->entityManager
            ->flush()
        ;

        $event->setNewCart($cart);
    }
}
