<?php

declare(strict_types=1);

namespace App\Domain\User\Controller;

use App\Domain\User\Form\UserFilterType;
use App\Domain\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UserAdminController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route(path: '/admin/user/list', name: 'admin_user_list', methods: ['GET'])]
    public function list(
        Request $request,
    ): Response {
        $page = $this->userService
            ->listAllPage(
                $request,
            )
        ;

        $form = $this->createForm(
            UserFilterType::class,
            $request->get(
                'user_filter',
                [],
            ),
        );

        return $this->render(
            'admin/user/list_users.html.twig',
            [
                'users' => $page->getItems(),
                'page' => $page->getCurrentPageNumber(),
                'totalPages' => $page->getPageCount(),
                'filterForm' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/admin/user/create', name: 'admin_user_create', methods: [
        'GET',
        'POST',
    ])]
    public function create(): Response
    {
    }

    #[Route(path: '/admin/user/edit/{id}', name: 'admin_user_edit', methods: [
        'GET',
        'POST',
    ])]
    public function edit(): Response
    {
    }

    #[Route(path: '/admin/user/delete/{id}', name: 'admin_user_delete', methods: ['DELETE'])]
    public function delete(): Response
    {
    }
}
