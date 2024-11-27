<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\NewUserMailEvent;
use App\Domain\User\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mime\Address;

#[AsEventListener]
class NewUserMailListener
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier,
    ) {
    }

    public function __invoke(NewUserMailEvent $event): void
    {
        $user = $event->getUser();

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(
                    new Address(
                        'info@cloudcart.com',
                        'System Email',
                    ),
                )
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig'),
        );
    }
}
