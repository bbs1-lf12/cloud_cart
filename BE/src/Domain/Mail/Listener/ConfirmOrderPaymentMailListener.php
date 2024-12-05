<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\ConfirmOrderPaymentMailEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ConfirmOrderPaymentMailListener extends AbstractMailListener
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(ConfirmOrderPaymentMailEvent $event): void
    {
        $this->sendEmail(
            "info@cloudcart.com",
            'System Email',
            $event->getTargetUser()
                ->getEmail(),
            'Order Payment Confirmation',
            'Mails/orders/confirm_order_mail.html.twig',
            [],
        );
    }
}
