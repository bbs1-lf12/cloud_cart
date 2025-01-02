<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ArticleFOService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleFOController extends AbstractController
{
    public function __construct(
        private readonly ArticleFOService $articleFOService,
    ) {
    }

    #[Route('/', name: 'article_list')]
    public function list(
        Request $request,
    ): Response {
        $page = $this->articleFOService
            ->listAllPage($request)
        ;
        return $this->render(
            'article/list.html.twig',
            [
                'articles' => $page->getItems(),
                'pager' => $page,
            ],
        );
    }
}
