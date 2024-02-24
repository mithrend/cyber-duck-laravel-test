<?php

namespace Database\Seeders;

use App\Enum\SystemProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed products in the database.
     */
    public function run(): void
    {
        Product::factory()->create([
            'name' => SystemProduct::GoldCoffee->value,
            'percent_profit_margin' => 25,
        ]);
    }
}
