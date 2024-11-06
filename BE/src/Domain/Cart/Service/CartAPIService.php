<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Listener\Event\ReorderCartCartItemsPositionsEvent;
use App\Domain\Cart\Security\CartItemVoter;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CartAPIService
{
    public function __construct(
        private readonly CartEntityQueryBuilderService $cartQueryBuilderService,
        private readonly CartEntityService $cartEntityService,
        private readonly PaginatorService $paginator,
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function getCartPage(Request $request): PaginationInterface
    {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;

        $qb = $this->cartQueryBuilderService
            ->getCartByUserQB($user)
        ;

        return $this->paginator->getApiPagination(
            $qb,
            $request,
        );
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function addCartItemToCart(
        Request $request,
    ): CartItem {
        $cart = $this->cartEntityService
            ->getCurrentCart()
        ;

        $newCartItem = new CartItem();
        $this->mapCartItemFromPayload(
            $newCartItem,
            $request->getPayload(),
        );

        // when cart-item does not exist in cart, persist it
        if (!$cart->hasCartItem($newCartItem)) {
            $newCartItem->setCart($cart);
            $newCartItem->setPosition(
                $cart->getCartItems()
                    ->count(),
            );
            $cart->addCartItem($newCartItem);
            $this->entityManager
                ->persist($newCartItem)
            ;
        } else {
            // when cart-item already exists in cart, update it
            $cartItem = $cart->getCartItem(
                $newCartItem->getArticle()
                    ->getId(),
            );
            $cartItem->setQuantity(
                $cartItem->getQuantity() + $newCartItem->getQuantity(),
            );
        }

        $this->entityManager
            ->flush()
        ;

        return $cartItem ?? $newCartItem;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function mapCartItemFromPayload(
        CartItem $cartItem,
        InputBag $payload,
    ): void {
        try {
            $articleId = $payload->get('article_id');
            $article = $this->entityManager
                ->getRepository(Article::class)
                ->find($articleId)
            ;
            if ($article === null) {
                throw new ApiException(
                    'Article not found',
                    404,
                );
            }
            $cartItem->setArticle($article);
            $cartItem->setQuantity($payload->get('amount'));
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
    public function editCartItem(
        Request $request,
    ): CartItem {
        $cartItem = $this->entityManager
            ->getRepository(CartItem::class)
            ->find($request->get('cartItemId'))
        ;

        if ($cartItem === null) {
            throw new ApiException(
                'Cart item not found',
                404,
            );
        }

        $this->authorizationChecker
            ->isGranted(
                CartItemVoter::EDIT,
                $cartItem,
            )
        ;

        $this->mapCartItemFromPayload(
            $cartItem,
            $request->getPayload(),
        );

        $event = new ReorderCartCartItemsPositionsEvent(
            $cartItem,
            $request->getPayload()
                ->get('position'),
        );
        $this->eventDispatcher
            ->dispatch($event)
        ;

        $this->entityManager
            ->flush()
        ;

        return $cartItem;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    public function deleteCartItem(Request $request)
    {
        $cartItem = $this->entityManager
            ->getRepository(CartItem::class)
            ->find($request->get('cartItemId'))
        ;

        if ($cartItem === null) {
            throw new ApiException(
                'Cart item not found',
                404,
            );
        }

        $this->authorizationChecker
            ->isGranted(
                CartItemVoter::DELETE,
                $cartItem,
            )
        ;

        $this->entityManager
            ->remove($cartItem)
        ;
        $this->entityManager
            ->flush()
        ;

        $event = new ReorderCartCartItemsPositionsEvent(
            $cartItem,
        );
        $this->eventDispatcher
            ->dispatch($event)
        ;

        return $cartItem;
    }
}
