<?php

declare(strict_types=1);

namespace App\Domain\Admin\Controller;

use App\Domain\Order\Service\DashboardOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly DashboardOrderService $dashboardOrderService,
    ) {
    }

    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render(
            'admin/dashboard/admin_dashboard.html.twig',
            [
                'total_revenue' => $this->dashboardOrderService
                    ->getRevenue(),
            ],
        );
    }
}
