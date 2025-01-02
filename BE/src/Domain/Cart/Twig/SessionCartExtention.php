<?php

declare(strict_types=1);

namespace App\Domain\Cart\Twig;

use App\Domain\Article\Entity\Article;
use App\Domain\Cart\Service\CartSessionService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SessionCartExtention extends AbstractExtension
{
    public function __construct(
        private readonly CartSessionService $cartSessionService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'getSessionCart',
                [
                    $this,
                    'getSessionCart',
                ],
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

        $articles = $articlesRepository->findBy([
            'id' => array_keys($cart),
        ]);

        return array_map(
            fn (
                $amount,
                $article,
            ) => [
                'amount' => $amount,
                'article' => $article,
            ],
            $cart,
            $articles,
        );
    }
}
