<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\SaleService;

use App\Enum\SystemProduct;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CalculateSellingPriceTest extends TestCase
{
    /** @test */
    public function can_not_calculate_selling_price_if_invalid_product_name(): void
    {
        $saleService = new SaleService();
        $invalidProductName = 'invalid_product_name';

        $builderMock = Mockery::mock(Builder::class);

        $productMock = Mockery::mock('alias:' . Product::class);
        $productMock->shouldReceive('where')
            ->with('name', $invalidProductName)
            ->once()
            ->andReturn($builderMock);

        $builderMock->shouldReceive('firstOrFail')
            ->once()
            ->andThrow(new ModelNotFoundException());

        $this->expectException(ModelNotFoundException::class);
        $saleService->calculateSellingPrice(1, 1, $invalidProductName);
    }

    /** @test */
    public function can_not_calculate_selling_price_if_invalid_quantity(): void
    {
        $saleService = new SaleService();
        $this->assertNull($saleService->calculateSellingPrice(0, 1, SystemProduct::GoldCoffee->value));
    }

    /** @test */
    public function can_not_calculate_selling_price_if_invalid_unit_cost(): void
    {
        $saleService = new SaleService();
        $this->assertNull($saleService->calculateSellingPrice(1, -1, SystemProduct::GoldCoffee->value));
    }

    public static function provideCalculateSellingPrice(): array
    {
        return [
            [1, 1000, SystemProduct::GoldCoffee->value, 2334],
            [2, 2050, SystemProduct::GoldCoffee->value, 6467],
            [5, 1200, SystemProduct::GoldCoffee->value, 9000],
        ];
    }

    /**
     * @test
     * @dataProvider provideCalculateSellingPrice
     */
    public function can_calculate_selling_price(int $quantity, int $unitCost, string $productName, int $expectedSellingPrice): void
    {
        $saleService = new SaleService();

        $builderMock = Mockery::mock(Builder::class);

        $productMock = Mockery::mock('alias:' . Product::class);
        $productMock->shouldReceive('where')
            ->with('name', $productName)
            ->once()
            ->andReturn($builderMock);

        $product = new Product();
        $product->percent_profit_margin = 25;

        $builderMock->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($product);

        $this->assertEquals($expectedSellingPrice, $saleService->calculateSellingPrice($quantity, $unitCost, $productName));
    }
}
