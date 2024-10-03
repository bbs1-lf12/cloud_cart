<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ScoreApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class ScoreApiController extends AbstractController
{
    public function __construct(
        private readonly ScoreApiService $scoreApiService
    )
    {
    }

    #[Route('/articles/{articleId}/score', name: 'api_v1_set_score', methods: ['POST'])]
    public function setScore(
        int $articleId,
        Request $request
    ): JsonResponse
    {
        $this->scoreApiService
            ->setScore(
                $articleId,
                $request
            );
        return new JsonResponse();
    }

    #[Route('/articles/{articleId}/score', name: 'api_v1_unset_score', methods: ['DELETE'])]
    public function unsetScore(
        int $articleId,
        Request $request
    ): JsonResponse
    {
        $this->scoreApiService
            ->unsetScore(
                $articleId,
                $request
            );
        return new JsonResponse();
    }
}
