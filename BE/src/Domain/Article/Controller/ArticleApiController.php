<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ArticleAPIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class ArticleApiController extends AbstractController
{
    public function __construct(
        readonly EntityManagerInterface $entityManager,
        readonly ArticleAPIService $articleAPIService,
        readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/articles', name: 'api_v1_list_all_articles', methods: ['GET'])]
    public function listArticles(
        Request $request,
    ): Response {
        /** @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination $page */
        $page = $this->articleAPIService
            ->listAllPage(
                $request,
            )
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $page->getItems(),
                    'json',
                    ['groups' => 'article:list'],
                ),
            headers: [
                'x-page' => $page->getCurrentPageNumber(),
                'x-total-pages' => $page->getPageCount(),
            ],
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name: 'api_v1_get_article', methods: ['GET'])]
    public function getArticle(
        int $id,
    ): Response {
        $article = $this->articleAPIService
            ->getArticleById($id)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list'],
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name: 'api_v1_edit_article', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN")]
    public function editArticle(
        int $id,
        Request $request,
    ): Response {
        $article = $this->articleAPIService
            ->editArticle(
                $id,
                $request,
            )
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list'],
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{id}', name: 'api_v1_delete_article', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteArticle(
        int $id,
    ): Response {
        $article = $this->articleAPIService
            ->deleteArticle($id)
        ;

        return new Response(
            content: $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list'],
                ),
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles', name: 'api_v1_create_article', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function createArticle(
        Request $request,
    ): Response {
        $article = $this->articleAPIService
            ->createArticle($request)
        ;

        return new Response(
            $this->serializer
                ->serialize(
                    $article,
                    'json',
                    ['groups' => 'article:list'],
                ),
        );
    }
}
