<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Common\Service\PaginatorService;
use App\Domain\Article\Service\ArticleQueryBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use \App\Domain\Article\Entity\Article;

#[Route('/api/v1')]
class ArticleApiController extends AbstractController
{
    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly ArticleQueryBuilderService $articleQueryBuilderService,
        readonly PaginatorService $paginator,
        readonly SerializerInterface $serializer
    )
    {}

    #[Route('/articles', methods: ['GET'])]
    public function list(
        Request $request
    ): JsonResponse
    {
        $qb = $this->articleQueryBuilderService
            ->selectAllArticlesQB();
        $qb = $this->articleQueryBuilderService
            ->addFilters(
                $qb,
                $request
            );

        $page = $this->paginator
            ->getApiPagination(
                $qb,
                $request
            );
        /** @var array<Article> $items */
        $items = $page->getItems();

        return new JsonResponse(
            [
                "page" => $page->getCurrentPageNumber(),
                "totalPages" => $page->getPageCount(),
                "articles" => $this->serializer
                    ->serialize(
                        $items,
                        'json',
                        ['groups' => 'article:list']
                    ),
            ],
        );
    }
}
