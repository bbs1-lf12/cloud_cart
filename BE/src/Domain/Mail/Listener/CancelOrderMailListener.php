<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\CancelOrderMailEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CancelOrderMailListener extends AbstractMailListener
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(CancelOrderMailEvent $event): void
    {
        $this->sendEmail(
            "info@cloudcart.com",
            'System Email',
            $event->getTargetUser()
                ->getEmail(),
            'Order Cancelled',
            'Mails/orders/cancel_order_mail.html.twig',
            [],
        );
    }
}
