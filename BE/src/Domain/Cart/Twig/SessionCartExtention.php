<?php

declare(strict_types=1);

namespace App\Domain\Cart\Twig;

use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Service\CartFOService;
use App\Domain\Cart\Service\CartSessionService;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SessionCartExtention extends AbstractExtension
{
    public function __construct(
        private readonly CartSessionService $cartSessionService,
        private readonly CartFOService $cartFOService,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'getSessionCart',
                $this->getSessionCart(...),
            ),
            new TwigFunction(
                'getUserCart',
                $this->getUserCart(...),
            ),
        ];
    }

    public function getSessionCart(): array
    {
        $cart = $this->cartSessionService
            ->getSessionCart()
        ;

        if (empty($cart)) {
            return [];
        }

        $articlesRepository = $this->entityManager
            ->getRepository(Article::class)
        ;

        $cartItem = [];
        foreach ($cart as $articleId => $amount) {
            $cartItem[$articleId] = [
                'amount' => $amount,
                'article' => $articlesRepository->findOneBy([
                    'id' => $articleId,
                ]),
            ];
        }

        return $cartItem;
    }

    public function getUserCart(): Cart
    {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;
        return $this->cartFOService
            ->getCart($user)
        ;
    }
}
