<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use Symfony\Component\HttpFoundation\Request;

class ScoreApiService
{
    public function setScore(
        int $articleId,
        Request $request
    ): void
    {
        // Set score
    }

    public function unsetScore(
        int $articleId,
        Request $request
    ): void
    {
        // Unset score
    }
}
