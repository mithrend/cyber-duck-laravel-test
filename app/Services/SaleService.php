<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;

class SaleService
{
    public function calculateSellingPrice(int $quantity, int $unitCost, string $productName): ?int
    {
        if ($quantity < 1 || $unitCost < 0) {
            return null;
        }

        $totalCost = $unitCost * $quantity;
        $shippingCost = 1000;
        $product = Product::where('name', $productName)->firstOrFail();
        $profitMargin = $product->percent_profit_margin / 100;

        return (int) ceil(($totalCost / (1 - $profitMargin)) + $shippingCost);
    }

    public function createSale(int $quantity, int $unitCost, string $productName): void
    {
        $sellingPrice = $this->calculateSellingPrice($quantity, $unitCost, $productName);

        Sale::create([
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'selling_price' => $sellingPrice,
            'product_id' => Product::where('name', $productName)->firstOrFail()->id,
        ]);
    }
}
