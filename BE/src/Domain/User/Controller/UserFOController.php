<?php

declare(strict_types=1);

namespace App\Domain\User\Controller;

use App\Domain\User\Entity\User;
use App\Domain\User\Form\UserType;
use App\Domain\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserFOController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly UserService $userService,
    ) {
    }

    #[Route('/settings', name: 'user_settings')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function showSettings(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security
            ->getUser()
        ;
        $oldPassword = $user->getPassword();

        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'editMode' => true,
            ],
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService
                ->update(
                    $user,
                    $oldPassword,
                )
            ;
            $this->addFlash(
                'success',
                'Settings updated',
            );
        }

        return $this->render(
            'user/settings.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}
