<?php

declare(strict_types=1);

namespace App\Domain\Article\Controller;

use App\Domain\Article\Service\CommentApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1')]
class CommentApiController extends AbstractController
{
    public function __construct(
        private readonly CommentApiService $commentApiService,
        readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/articles/{articleId}/comments', name: 'api_v1_list_all_comments', methods: ['GET'])]
    public function listAllComments(
        int $articleId,
        Request $request
    ): JsonResponse {
        $page = $this->commentApiService
            ->listAllCommentsByArticleId(
                $request,
                $articleId
            );

        return new JsonResponse(
            [
                "page" => $page->getCurrentPageNumber(),
                "totalPages" => $page->getPageCount(),
                "comments" => $this->serializer
                    ->serialize(
                        $page->getItems(),
                        'json',
                        ['groups' => 'comment:list']
                    ),
            ]
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{articleId}/comments', name: 'api_v1_create_comment', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function createComment(
        int $articleId,
        Request $request
    ): JsonResponse {
        $comment = $this->commentApiService
            ->createComment(
                $articleId,
                $request
            );

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $comment,
                    'json',
                    ['groups' => 'comment:list']
                )
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{articleId}/comments/{commentId}', name: 'api_v1_edit_comment', methods: ['PUT'])]
    #[IsGranted("ROLE_USER")]
    public function editComment(
        int $articleId,
        int $commentId,
        Request $request
    ): JsonResponse {
        $comment = $this->commentApiService
            ->editComment(
                $articleId,
                $commentId,
                $request
            );

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $comment,
                    'json',
                    ['groups' => 'comment:list']
                )
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    #[Route('/articles/{articleId}/comments/{commentId}', name: 'api_v1_delete_comment', methods: ['DELETE'])]
    #[IsGranted("ROLE_USER")]
    public function deleteComment(
        int $articleId,
        int $commentId
    ): JsonResponse {
        $comment = $this->commentApiService
            ->deleteComment(
                $articleId,
                $commentId
            );

        return new JsonResponse(
            $this->serializer
                ->serialize(
                    $comment,
                    'json',
                    ['groups' => 'comment:list']
                )
        );
    }
}
