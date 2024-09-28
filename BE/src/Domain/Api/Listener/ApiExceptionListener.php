<?php

declare(strict_types=1);

namespace App\Domain\Api\Listener;

use App\Domain\Api\Exceptions\ApiException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener]
class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof ApiException)) return;

        $event->stopPropagation();
        $event->setResponse(
            new JsonResponse(
                [
                    'message' => $exception->getMessage(),
                ],
                $exception->getHttpStatusCode()
            )
        );
    }
}
