<?php

declare(strict_types=1);

namespace App\Domain\Options\Controller;

use App\Domain\Options\Form\OptionsType;
use App\Domain\Options\Service\OptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class OptionsAdminController extends AbstractController
{
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

    #[Route('/admin/options', name: 'admin_options', methods: [
        'GET',
        'POST',
    ])]
    public function index(
        Request $request,
    ): Response {
        $options = $this->optionService
            ->getOptions()
        ;

        $form = $this->createForm(
            OptionsType::class,
            $options,
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->optionService
                    ->saveOptions($options);

                $this->addFlash(
                    'success',
                    'Options updated successfully.',
                );
            }
        }

        return $this->render(
            'admin/options/app_options.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}
