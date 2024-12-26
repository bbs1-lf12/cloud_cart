<?php

declare(strict_types=1);

namespace App\Domain\Options\Twig;

use App\Domain\Options\Entity\Options;
use App\Domain\Options\Service\OptionService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppOptionsExtension extends AbstractExtension
{
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'getAppOptions',
                [
                    $this,
                    'getAppOptions',
                ],
            ),
        ];
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAppOptions(): Options
    {
        return $this->optionService
            ->getOptions()
        ;
    }
}
