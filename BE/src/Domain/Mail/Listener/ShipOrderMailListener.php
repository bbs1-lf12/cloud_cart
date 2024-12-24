<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use App\Domain\Mail\Listener\Event\ShipOrderMailEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class ShipOrderMailListener extends AbstractMailListener
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(ShipOrderMailEvent $event): void
    {
        $this->sendEmail(
            "info@cloudcart.com",
            'System Email',
            $event->getTargetUser()
                ->getEmail(),
            'Order Shipped',
            'Mails/orders/ship_order_mail.html.twig',
            [],
        );
    }
}
