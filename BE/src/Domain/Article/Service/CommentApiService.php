<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Comment;
use App\Domain\Article\Security\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentApiService
{
    public function __construct(
        private readonly CommentQueryBuilderService $commentQueryBuilderService,
        private readonly PaginatorService $paginator,
        private readonly EntityManagerInterface $entityManager,
        private readonly ArticleAPIService $articleAPIService,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function listAllCommentsByArticleId(
        Request $request,
        int $articleId,
    ): PaginationInterface {
        $qb = $this->commentQueryBuilderService
            ->selectAllCommentsQB()
        ;
        $qb = $this->commentQueryBuilderService
            ->byArticleId(
                $qb,
                $articleId,
            )
        ;

        return $this->paginator
            ->getApiPagination(
                $qb,
                $request,
            )
        ;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function createComment(
        int $articleId,
        Request $request,
    ): Comment {
        $comment = new Comment();

        self::mapArticleFromPayload(
            $comment,
            $request->getPayload(),
        );

        $article = $this->articleAPIService
            ->getArticleById($articleId)
        ;
        $article->addComment($comment);
        $comment->setArticle($article);

        $this->entityManager
            ->persist($comment)
        ;
        $this->entityManager
            ->flush()
        ;

        return $comment;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private static function mapArticleFromPayload(
        Comment $comment,
        InputBag $payload,
    ): void {
        try {
            $comment->setContent($payload->get('content'));
        } catch (\Throwable $e) {
            throw new ApiException(
                'Invalid payload',
                400,
            );
        }
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function editComment(
        int $articleId,
        int $commentId,
        Request $request,
    ): Comment {
        // TODO: Move to CommentEntityService
        $comment = $this->getCommentById(
            $commentId,
            $articleId,
        );

        $this->authorizationChecker
            ->isGranted(
                CommentVoter::EDIT,
                $comment,
            )
        ;

        self::mapArticleFromPayload(
            $comment,
            $request->getPayload(),
        );

        $this->entityManager
            ->flush()
        ;

        return $comment;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function getCommentById(
        int $commentId,
        int $articleId,
    ): Comment {
        $repository = $this->entityManager
            ->getRepository(Comment::class)
        ;
        $comment = $repository
            ->find($commentId)
        ;

        $article = $this->articleAPIService
            ->getArticleById($articleId)
        ;

        if (
            $comment === null ||
            !$article->hasComment($comment)
        ) {
            throw new ApiException(
                'Comment not found',
                404,
            );
        }

        return $comment;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function deleteComment(
        int $articleId,
        int $commentId,
    ): Comment {
        $comment = $this->getCommentById(
            $commentId,
            $articleId,
        );

        $this->authorizationChecker
            ->isGranted(
                CommentVoter::DELETE,
                $comment,
            )
        ;

        $this->entityManager
            ->remove($comment)
        ;
        $this->entityManager
            ->flush()
        ;

        return $comment;
    }
}
