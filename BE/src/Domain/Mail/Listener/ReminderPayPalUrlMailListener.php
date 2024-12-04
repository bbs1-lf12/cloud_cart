<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsEventListener]
class ReminderPayPalUrlMailListener
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(ReminderPayPalUrlMailEvent $event): void
    {
        $user = $event->getTargetUser();
        $order = $event->getOrder();

        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    'info@cloudcart.com',
                    'System Email',
                ),
            )
            ->to($user->getEmail())
            ->subject('PayPal Payment Reminder')
            ->htmlTemplate('Mails/Payment/paypal_payment_reminder.html.twig')
        ;

        $context = $email->getContext();
        $context['user'] = $user;
        $context['order'] = $order;
        $email->context($context);

        $this->mailer->send($email);
    }
}
