<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\ReminderPayPalUrlMailEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ReminderPayPalUrlMailListener extends AbstractMailListener
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(ReminderPayPalUrlMailEvent $event): void
    {
        $this->sendEmail(
            "info@cloudcart.com",
            'System Email',
            $event->getTargetUser()
                ->getEmail(),
            'PayPal Payment Reminder',
            'Mails/Payment/paypal_payment_reminder.html.twig',
            [
                'user' => $event->getTargetUser(),
                'order' => $event->getOrder(),
            ],
        );
    }
}
