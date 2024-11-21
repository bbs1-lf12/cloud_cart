<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\ArticleService;
use App\Domain\Article\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted('ROLE_ADMIN')]
class FileAPIController extends AbstractController
{
    public function __construct(
        private readonly ArticleService $articleService,
    ) {
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/api/v1/articles/{articleId}/image/upload', name: 'app_v1_upload_file', methods: ['POST'])]
    public function uploadFile(
        int $articleId,
        Request $request,
    ): JsonResponse {
        $filename = $this->articleService
            ->addImage(
                $articleId,
                $request->files
                    ->get('file'),
            )
        ;

        return new JsonResponse(
            data: [
                'filename' => $filename,
            ],
        );
    }
}
