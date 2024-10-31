<?php

declare(strict_types=1);

namespace App\Domain\Api\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[AsEventListener]
class ApiAccessDeniedHttpExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof AccessDeniedHttpException)) {
            return;
        }

        $event->stopPropagation();
        $event->setResponse(
            new JsonResponse(
                [
                    'message' => 'Access Denied',
                ],
                $exception->getStatusCode()
            )
        );
    }
}
