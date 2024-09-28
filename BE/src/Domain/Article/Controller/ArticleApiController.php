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

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name:'api_v1_edit_article' ,methods: ['PUT'])]
    public function editArticle(
        int $id,
        Request $request
    ): JsonResponse {
        $article = $this->articleAPIService
            ->editArticle(
                $id,
                $request
            );

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list']
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name:'api_v1_delete_article' ,methods: ['DELETE'])]
    public function deleteArticle(
        int $id
    ): JsonResponse {
        $article = $this->articleAPIService
            ->deleteArticle($id);

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list']
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles', name:'api_v1_create_article' ,methods: ['POST'])]
    public function createArticle(
        Request $request
    ): JsonResponse {
        $article = $this->articleAPIService
            ->createArticle($request);

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
