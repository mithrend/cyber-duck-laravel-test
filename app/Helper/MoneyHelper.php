<?php

declare(strict_types=1);

namespace App\Helper;

class MoneyHelper
{
    public static function toMinorCurrencyInt(string $value): int
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Value must be numeric');
        }

        $parts = explode('.', $value);
        $parts[1] = str_pad(substr($parts[1] ?? '', 0, 2), 2, '0', STR_PAD_RIGHT);

        $minorValue = $parts[0] . $parts[1];

        return (int) $minorValue;
    }
}
