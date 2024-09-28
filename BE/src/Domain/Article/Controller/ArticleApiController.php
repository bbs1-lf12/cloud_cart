<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ArticleAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class ArticleApiController extends AbstractController
{
    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly ArticleAPIService $articleAPIService,
        readonly SerializerInterface $serializer
    )
    {}

    #[Route('/articles', name:'api_v1_list_all_articles' , methods: ['GET'])]
    public function listArticles(
        Request $request,
    ): JsonResponse
    {
        $page = $this->articleAPIService
            ->listAllPage(
                $request
            );

        return new JsonResponse(
            [
                "page" => $page->getCurrentPageNumber(),
                "totalPages" => $page->getPageCount(),
                "articles" => $this->serializer
                    ->serialize(
                        $page->getItems(),
                        'json',
                        ['groups' => 'article:list']
                    ),
            ],
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name:'api_v1_get_article' ,methods: ['GET'])]
    public function getArticle(
        int $id
    ): JsonResponse {
        $article = $this->articleAPIService
            ->getArticleById($id);

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list']
                ),
        );
    }
}
