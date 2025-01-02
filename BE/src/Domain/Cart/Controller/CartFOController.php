<?php

declare(strict_types=1);

namespace App\Domain\Cart\Controller;

use App\Domain\Cart\Service\CartSessionService;
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
}
