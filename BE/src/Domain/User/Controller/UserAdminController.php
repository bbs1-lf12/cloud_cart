<?php

declare(strict_types=1);

namespace App\Domain\User\Controller;

use App\Domain\User\Form\UserFilterType;
use App\Domain\User\Form\UserType;
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
    public function create(
        Request $request,
    ): Response {
        $form = $this->createForm(
            UserType::class,
        );

        $form->handleRequest(
            $request,
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService
                ->create(
                    $form->getData(),
                )
            ;

            return $this->redirectToRoute(
                'admin_user_list',
            );
        }

        return $this->render(
            'admin/user/create_user.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/admin/user/edit/{id}', name: 'admin_user_edit', methods: [
        'GET',
        'POST',
    ])]
    public function edit(
        int $id,
        Request $request,
    ): Response {
        $user = $this->userService
            ->getUserById(
                $id,
            )
        ;
        $oldPassword = $user->getPassword();

        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'editMode' => true,
            ],
        );

        $form->handleRequest(
            $request,
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService
                ->update(
                    $form->getData(),
                    $oldPassword
                )
            ;

            return $this->redirectToRoute(
                'admin_user_list',
            );
        }

        return $this->render(
            'admin/user/edit_user.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/admin/user/delete/{id}', name: 'admin_user_delete', methods: ['DELETE'])]
    public function delete(): Response
    {
    }
}
