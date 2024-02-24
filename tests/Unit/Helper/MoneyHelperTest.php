<?php

declare(strict_types=1);

namespace Tests\Unit\Helper;

use App\Helper\MoneyHelper;
use PHPUnit\Framework\TestCase;

class MoneyHelperTest extends TestCase
{
    public static function non_numeric_values(): array
    {
        return [
            'Only alpha characters' => ['foo'],
            'Multiple Decimal Separators' => ['1.2.3'],
            'Comma Separated' => ['1,2'],
            'Leading number followed by alpha characters' => ['1foo'],
            'Leading alpha characters followed by number' => ['foo1'],
        ];
    }

    /**
     * @test
     * @dataProvider non_numeric_values
     */
    public function only_accepts_numeric_values_when_converting_to_minor_currency_integer(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MoneyHelper::toMinorCurrencyInt($value);
    }

    public static function valid_values(): array
    {
        return [
            'integer' => ['1', 100],
            'negative integer' => ['-1', -100],
            'integer with leading 0' => ['01', 100],
            'zero' => ['0', 0],
            'one decimal place' => ['1.2', 120],
            'two decimal places' => ['1.23', 123],
            'three decimal places' => ['1.234', 123],
            'negative two decimal places' => ['-1.23', -123],
            'two decimal places with leading 0' => ['01.23', 123],
            'decimal less than one' => ['0.23', 23],
        ];
    }

    /**
     * @test
     * @dataProvider valid_values
     */
    public function can_convert_to_minor_currency_integer(string $value, int $expected): void
    {
        $this->assertEquals($expected, MoneyHelper::toMinorCurrencyInt($value));
    }
}
