<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartFOService;
use App\Domain\Cart\Service\CartSessionService;
use App\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartFOController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly CartSessionService $cartSessionService,
        private readonly CartFOService $cartFOService,
    ) {
    }

    #[Route('/cart', name: 'cart_add', methods: ['POST'])]
    public function addToCart(
        Request $request,
    ): Response {
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            $this->cartSessionService
                ->addArticle($request)
            ;
        }

        return $this->redirectToRoute('article_list');
    }

    #[Route('/cart', name: 'cart_show', methods: ['GET'])]
    public function showCart(): Response
    {
        /** @var User|null $user */
        $user = $this->security
            ->getUser()
        ;

        if ($user === null) {
            return $this->render(
                'cart/sessionCart.html.twig',
            );
        }

        return $this->render(
            'cart/userCart.html.twig',
            [
                'cart' => $this->cartFOService
                    ->getCart($user),
            ],
        );
    }
}
