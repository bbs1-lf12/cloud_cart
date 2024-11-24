<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ScoreAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
#[IsGranted("ROLE_USER")]
class ScoreApiController extends AbstractController
{
    public function __construct(
        private readonly ScoreAPIService $scoreApiService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{articleId}/score', name: 'api_v1_set_score', methods: ['POST'])]
    public function setScore(
        int $articleId,
        Request $request
    ): Response {
        $score = $this->scoreApiService
            ->createScore(
                $articleId,
                $request
            );
        return new Response(
            content: $this->serializer
                ->serialize(
                    $score,
                    'json',
                    ['groups' => 'score:list']
                )
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{articleId}/score/{scoreId}', name: 'api_v1_unset_score', methods: ['DELETE'])]
    public function unsetScore(
        int $articleId,
        int $scoreId
    ): Response {
        $score = $this->scoreApiService
            ->deleteScore(
                $articleId,
                $scoreId
            );
        return new Response(
            content: $this->serializer
                ->serialize(
                    $score,
                    'json',
                    ['groups' => 'score:list']
                )
        );
    }
}
