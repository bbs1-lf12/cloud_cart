<?php

declare(strict_types=1);

namespace App\Domain\Article\Twig;

use App\Domain\Options\Service\OptionService;
use Symfony\Component\Intl\Currencies;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArticleExtension extends AbstractExtension
{
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'price',
                [
                    $this,
                    'formatPrice',
                ],
            ),
        ];
    }

    public function formatPrice(int $priceInCents): string
    {
        $price = number_format(
            $priceInCents / 100,
            2,
            ',',
            '.',
        );
        $currency = $this->optionService
            ->getOptions()
            ->getCurrency()
        ;
        $symbol = Currencies::getSymbol($currency);
        return $symbol . ' ' . $price;
    }
}
