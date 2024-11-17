<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Form\ArticleFilterType;
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
        );

        return $this->render(
            'admin/article/list_articles.html.twig',
            [
                'articles' => $page->getItems(),
                'page' => $page->getCurrentPageNumber(),
                'totalPages' => $page->getPageCount(),
                'form' => $form->createView(),
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

    #[Route('/admin/article/{id}', name: 'admin_article_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('admin/article/show_article.html.twig');
    }

    #[Route('/admin/article/{id}/edit', name: 'admin_article_edit', methods: [
        'GET',
        'POST',
    ])]
    public function edit(int $id): Response
    {
        return $this->render('admin/article/edit_article.html.twig');
    }
}
