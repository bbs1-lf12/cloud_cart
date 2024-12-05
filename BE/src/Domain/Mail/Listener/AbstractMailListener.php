<?php

declare(strict_types=1);

namespace App\Domain\Mail\Listener;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

abstract class AbstractMailListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    protected function sendEmail(
        string $from,
        string $fromName,
        string $to,
        string $subject,
        string $template,
        array $context,
    ): void {
        $email = (new TemplatedEmail())
            ->from(
                new Address(
                    $from,
                    $fromName,
                ),
            )
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
        ;

        $context = $email->getContext();
        $context = array_merge($context, $context);
        $email->context($context);

        $this->mailer->send($email);
    }
}
