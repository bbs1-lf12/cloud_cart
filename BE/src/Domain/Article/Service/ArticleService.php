<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ArticleService
{
    public function __construct(
        private readonly PaginatorService $paginator,
        private readonly ArticleQueryBuilderService $articleQueryBuilderService,
        private readonly EntityManagerInterface $entityManager,
        private readonly ImageService $imageService,
    ) {
    }

    public function listAllPage(
        Request $request,
    ): PaginationInterface {
        $qb = $this->articleQueryBuilderService
            ->selectAllArticlesQB()
        ;
        $qb = $this->articleQueryBuilderService
            ->addFilters(
                $qb,
                $request,
            )
        ;

        return $this->paginator
            ->getPagination(
                $qb,
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
        $repository = $this->entityManager
            ->getRepository(Article::class)
        ;
        /** @var Article $article */
        $article = $repository
            ->find($id)
        ;

        if ($article === null) {
            throw new ApiException(
                'Article not found',
                404,
            );
        }

        return $article;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function addImage(
        int $articleId,
        UploadedFile $file,
    ): string {
        $article = $this->getArticleById($articleId);
        $fileName = $this->imageService
            ->upload($file)
        ;
        $article->setImage($fileName);
        $this->entityManager
            ->flush()
        ;
        return $fileName;
    }
}
