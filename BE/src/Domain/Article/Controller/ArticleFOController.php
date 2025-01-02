<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleFOController extends AbstractController
{
    #[Route('/', name: 'article_list')]
    public function list(): Response {
        return $this->render(
          'article/list.html.twig',
        );
    }
}
