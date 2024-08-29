<?php

declare(strict_types=1);

namespace App\Domain\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/')]
    public function test(): Response
    {
        return $this->render("base.html.twig");
    }
}
