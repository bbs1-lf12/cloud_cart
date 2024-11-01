<?php

declare(strict_types=1);

namespace App\Domain\Article\Security;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Comment;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    public const EDIT = 'comment.edit';
    public const DELETE = 'comment.delete';

    public function __construct(
        private readonly Security $security,
    ) {
    }

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (!$subject instanceof Comment) {
            return false;
        }

        if (!in_array(
            $attribute,
            [
                self::EDIT,
                self::DELETE,
            ],
        )) {
            return false;
        }

        return true;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
    ): bool {
        $comment = $subject;
        $currentUser = $token->getUser();

        return match ($attribute) {
            self::EDIT => $this->canEdit(
                $comment,
                $currentUser,
            ),
            self::DELETE => $this->canDelete(
                $comment,
                $currentUser,
            ),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function canEdit(
        Comment $comment,
        mixed $currentUser,
    ): bool {
        if (
            $comment->getUser() === $currentUser
            || $this->security->isGranted("ROLE_ADMIN")
        ) {
            return true;
        }
        throw new ApiException(
            'Cannot edit comments from other users',
            403,
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function canDelete(
        Comment $comment,
        mixed $currentUser,
    ): bool {
        if (
            $comment->getUser() === $currentUser
            || $this->security->isGranted("ROLE_ADMIN")
        ) {
            return true;
        }
        throw new ApiException(
            'Cannot delete comments from other users',
            403,
        );
    }
}
