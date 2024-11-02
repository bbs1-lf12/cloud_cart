<?php

declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Common\Service\PaginatorService;
use App\Domain\Api\Exceptions\ApiException;
use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Listener\Event\CreateCartEvent;
use App\Domain\Cart\Listener\Event\GetCartItemPositionEvent;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

class CartAPIService
{
    public function __construct(
        private readonly CartEntityQueryBuilderService $cartQueryBuilderService,
        private readonly PaginatorService $paginator,
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
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
    public function addItemToCartId(
        Request $request,
    ): CartItem {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;

        /** @var Cart|null $cart */
        $cart = $this->cartQueryBuilderService
            ->getCartByUserQB($user)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($cart === null) {
            $event = new CreateCartEvent();
            $this->eventDispatcher
                ->dispatch($event)
            ;
            $cart = $event->getNewCart();
        }

        $cartItem = new CartItem();
        $cartItem->setCart($cart);
        $this->mapArticleFromPayload(
            $cartItem,
            $request->getPayload(),
        );

        $event = new GetCartItemPositionEvent($cart->getId());
        $this->eventDispatcher
            ->dispatch($event)
        ;
        $cartItem->setPosition($event->getPosition());

        $this->entityManager
            ->persist($cartItem)
        ;
        $this->entityManager
            ->flush()
        ;

        return $cartItem;
    }

    /**
     * @throws \App\Domain\Api\Exceptions\ApiException
     */
    private function mapArticleFromPayload(
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
}
