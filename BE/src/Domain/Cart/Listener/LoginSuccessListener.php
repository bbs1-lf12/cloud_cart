<?php

declare(strict_types=1);

namespace App\Domain\Cart\Listener;

use App\Domain\Cart\Service\CartFOService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

#[AsEventListener]
class LoginSuccessListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CartFOService $cartFOService,
    )
    {}

    public function __invoke(InteractiveLoginEvent $event): void
    {
        /** @var \App\Domain\User\Entity\User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $this->cartFOService->mergeCartSessionToUserCart(
            $this->requestStack->getSession()->getId(),
            $user,
        );
    }
}
