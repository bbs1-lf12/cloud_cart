<?php

declare(strict_types=1);

namespace App\Common\Utils;

use Symfony\Component\Intl\Currencies;

class CurrencyUtils
{
    public static function generateCurrencyName(string $name): string
    {
        return Currencies::getSymbol($name) . ' ' . Currencies::getName($name);
    }

    public static function getSupportedCurrencies(): array
    {
        return explode(
            ',',
            $_ENV['INTL_AVAILABLE_CURRENCIES'],
        );
    }

    public static function generateNameCodeAssoc(): array
    {
        $assoc = [];
        foreach (CurrencyUtils::getSupportedCurrencies() as $currency) {
            $assoc[CurrencyUtils::generateCurrencyName($currency)] = Currencies::getNumericCode($currency);
        }
        return $assoc;
    }
}
