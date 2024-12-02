<?php

declare(strict_types=1);

namespace App\Domain\Article\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArticleExtension extends AbstractExtension
{
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
        return number_format(
            $priceInCents / 100,
            2,
            ',',
            '.',
        );
    }
}
