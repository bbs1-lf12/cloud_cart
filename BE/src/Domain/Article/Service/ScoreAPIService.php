<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Score;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

class ScoreAPIService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ArticleAPIService $articleAPIService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function createScore(
        int $articleId,
        Request $request
    ): Score {
        $article = $this->articleAPIService
            ->getArticleById($articleId);
        $score = new Score();
        $score->setArticle($article);
        self::mapScoreFromPayload(
            $score,
            $request->getPayload()
        );

        $this->entityManager
            ->persist($score);
        $this->entityManager
            ->flush();
        return $score;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function deleteScore(
        int $articleId,
        int $scoreId
    ): Score {
        $article = $this->articleAPIService
            ->getArticleById($articleId);
        $score = $this->getScoreById($scoreId);
        if ($article->hasScore($score)) {
            $this->entityManager
                ->remove($score);
            $this->entityManager
                ->flush();
        } else {
            throw new ApiException(
                'Score not found',
                404
            );
        }
        return $score;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function mapScoreFromPayload(
        Score $score,
        InputBag $payload,
    ): void {
        try {
            $score->setScore($payload->get('score'));
        } catch (\Throwable $e) {
            throw new ApiException(
                'Invalid payload',
                400
            );
        }
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function getScoreById(
        int $id
    ): Score {
        $repository = $this->entityManager
            ->getRepository(Score::class);
        $score = $repository
            ->find($id);

        if ($score === null) {
            throw new ApiException(
                'Score not found',
                404
            );
        }

        return $score;
    }
}
