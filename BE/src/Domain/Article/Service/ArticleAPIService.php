<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

class ArticleAPIService
{
    public function __construct(
        private readonly ArticleService $articleService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function listAllPage(
        Request $request,
    ): PaginationInterface {
        return $this->articleService
            ->listAllPage(
                $request,
            )
        ;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function getArticleById(
        int $id,
    ): Article {
        return $this->articleService
            ->getArticleById($id)
        ;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function editArticle(
        int $id,
        Request $request,
    ): Article {
        // TODO: Move to ArticleEntityService
        $article = $this->getArticleById($id);

        self::mapArticleFromPayload(
            $article,
            $request->getPayload(),
        );

        $this->entityManager
            ->flush()
        ;

        return $article;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function deleteArticle(
        int $id,
    ): Article {
        $article = $this->getArticleById($id);

        $this->entityManager
            ->remove($article)
        ;
        $this->entityManager
            ->flush()
        ;

        return $article;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function createArticle(
        Request $request,
    ): Article {
        $article = new Article();

        self::mapArticleFromPayload(
            $article,
            $request->getPayload(),
        );
        $article->setIsEnabled(false);

        $this->entityManager
            ->persist($article)
        ;
        $this->entityManager
            ->flush()
        ;

        return $article;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function mapArticleFromPayload(
        Article $article,
        InputBag $payload,
    ): void {
        try {
            $article->setTitle($payload->get('title'));
            $article->setDescription($payload->get('description'));
            $article->setPriceInCents($payload->get('priceInCents'));
            $article->setStock($payload->get('stock'));
            $article->setIsFeatured($payload->get('isFeatured'));
            $article->setScore($payload->get('score'));
        } catch (\Throwable $e) {
            throw new ApiException(
                'Invalid payload',
                400,
            );
        }
    }
}
