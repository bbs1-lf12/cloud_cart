<?php

declare(strict_types=1);

namespace App\Domain\Api\Exceptions;

class ApiException extends \Exception
{
    private int $httpStatusCode;

    public function __construct(
        string $message,
        int $httpStatusCode
    ) {
        parent::__construct($message);
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function setHttpStatusCode(int $httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }
}
