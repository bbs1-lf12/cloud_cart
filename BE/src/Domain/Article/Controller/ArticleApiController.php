<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class ArticleApiController extends AbstractController
{
    public function __construct(
        readonly EntityManagerInterface $entityManager
    )
    {}

    #[Route('/articles', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // build query
            // all articles
            // filters
        // pagination
        return new JsonResponse(
            [
                "message" => "List of articles"
            ]
        );
    }
}
