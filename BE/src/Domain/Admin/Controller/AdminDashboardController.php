<?php

declare(strict_types=1);

namespace App\Domain\Admin\Controller;

use App\Domain\Order\Service\DashboardOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractController
{
    public function __construct(
        private readonly DashboardOrderService $dashboardOrderService,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/dashboard/revenue/{days}', name: 'app_domain_admin_dashboard_retrieve_revenue')]
    public function retrieveRevenue(
        int $days,
    ): JsonResponse {
        $dataSet = $this->dashboardOrderService
            ->getRevenuePerDayDataSet($days)
        ;
        return new JsonResponse([
            ...$dataSet,
        ]);
    }
}
