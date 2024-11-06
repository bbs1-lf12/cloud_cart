<?php

declare(strict_types=1);

namespace App\Domain\Cart\Security;

use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Cart\Entity\CartItem;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CartItemVoter extends Voter
{
    public const EDIT = 'cart_item.edit';
    public const DELETE = 'cart_item.delete';

    public function __construct(
        private readonly Security $security,
    ) {
    }

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (!$subject instanceof CartItem) {
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
        $cartItem = $subject;
        $currentUser = $token->getUser();

        return match ($attribute) {
            self::EDIT => $this->canEdit(
                $cartItem,
                $currentUser,
            ),
            self::DELETE => $this->canDelete(
                $cartItem,
                $currentUser,
            ),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function canEdit(
        CartItem $cartItem,
        mixed $currentUser,
    ): bool {
        if (
            $cartItem->getCart()
                ->getUser() === $currentUser
            || $this->security->isGranted("ROLE_ADMIN")
        ) {
            return true;
        }
        throw new ApiException(
            'Cannot edit cart items from other users',
            403,
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function canDelete(
        CartItem $cartItem,
        mixed $currentUser,
    ): bool {
        if (
            $cartItem->getCart()
                ->getUser() === $currentUser
            || $this->security->isGranted("ROLE_ADMIN")
        ) {
            return true;
        }
        throw new ApiException(
            'Cannot delete cart items from other users',
            403,
        );
    }
}
