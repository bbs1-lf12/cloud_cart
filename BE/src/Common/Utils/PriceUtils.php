<?php

declare(strict_types=1);

namespace App\Common\Utils;

class PriceUtils
{
    public static function toCents(string|float|int $price): int
    {
        if (!is_numeric($price)) {
            throw new \Exception('Price must be a number');
        }

        if (is_string($price)) {
            $price = (float) $price;
        }

        return (int) ($price * 100);
    }

    /**
     * @throws \Exception
     */
    public static function toPrice(string|float|int $price): float {
        if (!is_numeric($price)) {
            throw new \Exception('Price must be a number');
        }

        if (is_string($price)) {
            $price = (float) $price;
        }

        return (float) ($price / 100);
    }
}
