<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ShowSales;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowSalesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ShowSales::class)
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/sales')
            ->assertSeeLivewire(ShowSales::class);
    }

    /** @test */
    public function displays_sales(): void
    {
        $productOne = Product::factory()->create([
            'name' => 'Test Coffee One',
            'percent_profit_margin' => 10,
        ]);

        $productTwo = Product::factory()->create([
            'name' => 'Test Coffee Two',
            'percent_profit_margin' => 15,
        ]);

        $this->travel(-1)->days();

        $saleOne = Sale::factory()->create([
            'quantity' => 1,
            'unit_cost' => 100,
            'selling_price' => 1009,
            'product_id' => $productOne->id,
        ]);

        $this->travelBack();

        $saleTwo = Sale::factory()->create([
            'quantity' => 3,
            'unit_cost' => 1234,
            'selling_price' => 5678,
            'product_id' => $productTwo->id,
        ]);

        Livewire::test(ShowSales::class)
            ->assertSee('Test Coffee One')
            ->assertSee('1')
            ->assertSee('£1.00')
            ->assertSee('£10.09')
            ->assertSee($saleOne->created_at->format('Y-m-d H:i:s'))
            ->assertSee('Test Coffee Two')
            ->assertSee('3')
            ->assertSee('£12.34')
            ->assertSee('£56.78')
            ->assertSee($saleTwo->created_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function sales_updated_event_updates_view(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Coffee',
            'percent_profit_margin' => 10,
        ]);


        Sale::factory()->create([
            'quantity' => 1,
            'unit_cost' => 100,
            'selling_price' => 1009,
            'product_id' => $product->id,
        ]);

        $livewire = Livewire::test(ShowSales::class);

        $livewire->assertViewHas('sales', fn ($sales) => $sales->count() === 1);

        Sale::factory()->create([
            'quantity' => 3,
            'unit_cost' => 1234,
            'selling_price' => 5678,
            'product_id' => $product->id,
        ]);

        $livewire
            ->dispatch('sales-updated')
            ->assertViewHas('sales', fn ($sales) => $sales->count() === 2);
    }
}
