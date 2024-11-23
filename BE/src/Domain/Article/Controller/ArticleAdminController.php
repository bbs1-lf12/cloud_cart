<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Form\ArticleFilterType;
use App\Domain\Article\Form\ArticleType;
use App\Domain\Article\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ArticleAdminController extends AbstractController
{
    public function __construct(
        private readonly ArticleService $articleService,
    ) {
    }

    #[Route('/admin/article/list', name: 'admin_article_list', methods: ['GET'])]
    public function list(
        Request $request,
    ): Response {
        $page = $this->articleService
            ->listAllPage(
                $request,
            )
        ;

        $form = $this->createForm(
            ArticleFilterType::class,
            $request->get('article_filter', []),
        );

        return $this->render(
            'admin/article/list_articles.html.twig',
            [
                'articles' => $page->getItems(),
                'page' => $page->getCurrentPageNumber(),
                'totalPages' => $page->getPageCount(),
                'filterForm' => $form->createView(),
            ],
        );
    }

    #[Route('/admin/article', name: 'admin_article_create', methods: [
        'GET',
        'POST',
    ])]
    public function create(): Response
    {
        return $this->render('admin/article/create_article.html.twig');
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/admin/article/{id}', name: 'admin_article_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $article = $this->articleService
            ->getArticleById($id);

        return $this->render(
            'admin/article/show_article.html.twig',
            [
                'article' => $article,
            ],
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/admin/article/{id}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
    ): Response {
        $article = $this->articleService
            ->getArticleById($id)
        ;

        $form = $this->createForm(
            ArticleType::class,
            $article,
        );

        $form->handleRequest($request);

        if (
            $form->isSubmitted()
            && $form->isValid()
        ) {
            $this->articleService
                ->updateArticle(
                    $article,
                    $form,
                )
            ;

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render(
            'admin/article/edit_article.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/admin/article/{id}/delete', name: 'admin_article_delete', methods: ['GET'])]
    public function delete(
        int $id,
    ): Response {
        $this->articleService
            ->deleteArticle($id)
        ;

        return $this->redirectToRoute('admin_article_list');
    }
}
