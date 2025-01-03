<?php

declare(strict_types=1);

namespace App\Domain\User\Controller;

use App\Domain\Mail\Listener\Event\NewUserMailEvent;
use App\Domain\User\Entity\User;
use App\Domain\User\Form\RegistrationFormType;
use App\Domain\User\Security\EmailVerifier;
use App\Domain\User\Service\UserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRegistrationService $userRegistrationService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EmailVerifier $emailVerifier,
    ) {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(
            RegistrationFormType::class,
            $user,
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $form->get('plainPassword')
                    ->getData(),
            );
            $this->userRegistrationService
                ->registerUser($user)
            ;

            $event = new NewUserMailEvent($user);
            $this->eventDispatcher
                ->dispatch($event)
            ;

            return $this->redirectToRoute('article_list');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form,
            ],
        );
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation(
                $request,
                $this->getUser(),
            );
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash(
                'verify_email_error',
                $exception->getReason(),
            );

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash(
            'success',
            'Your email address has been verified.',
        );

        return $this->redirectToRoute('app_register');
    }
}
