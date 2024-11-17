<?php

declare(strict_types=1);

namespace App\Domain\Admin\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener]
class LoginSuccessListener
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(LoginSuccessEvent $event)
    {
        $user = $event->getUser();

        if (in_array(
            'ROLE_ADMIN',
            $user->getRoles(),
            true,
        )) {
            $url = $this->urlGenerator->generate('admin_index');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
