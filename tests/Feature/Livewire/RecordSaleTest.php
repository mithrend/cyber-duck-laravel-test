<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Enum\SystemProduct;
use App\Livewire\RecordSale;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RecordSaleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully(): void
    {
        Livewire::test(RecordSale::class)
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/sales')
            ->assertSeeLivewire(RecordSale::class);
    }

    /** @test */
    public function can_create_a_sale(): void
    {
        $this->seed(ProductSeeder::class);
        $this->assertEquals(0, Sale::count());

        $arabicCoffee = Product::where('name', SystemProduct::ArabicCoffee->value)->first();

        Livewire::test(RecordSale::class)
            ->set('quantity', 1)
            ->set('unitCost', '10.00')
            ->set('productName', $arabicCoffee->name)
            ->call('save');

        $this->assertEquals(1, Sale::count());

        $sale = Sale::first();
        $this->assertEquals($arabicCoffee->id, $sale->product_id);
        $this->assertEquals(1, $sale->quantity);
        $this->assertEquals(1000, $sale->unit_cost);
    }

    /** @test */
    public function creating_sale_dispatches_sales_updated_event(): void
    {

        $this->seed(ProductSeeder::class);
        $this->assertEquals(0, Sale::count());

        Livewire::test(RecordSale::class)
            ->set('quantity', 1)
            ->set('unitCost', '10.00')
            ->call('save')
            ->assertDispatched('sales-updated');
    }

    public static function quantityIsInvalid(): array
    {
        return [
            'quantity is less than 1' => [0, 'min'],
            'quantity is empty' => [null, 'required'],
        ];
    }

    /**
     * @test
     * @dataProvider quantityIsInvalid
     */
    public function quantity_is_validated(?int $quantity, string $rule): void
    {
        Livewire::test(RecordSale::class)
            ->set('quantity', $quantity)
            ->call('save')
            ->assertHasErrors(['quantity' => $rule]);
    }

    public static function unitCostIsInvalid(): array
    {
        return [
            'unit cost is less than 0' => ['-1', 'min'],
            'unit cost is empty' => ['', 'required'],
            'unit cost is not a number' => ['foo', 'decimal:0,2'],
            'unit cost has too many decimal' => ['10.001', 'decimal:0,2'],
            'unit cost is in scientific notation' => ['1e3', 'decimal:0,2'],
        ];
    }

    /**
     * @test
     * @dataProvider unitCostIsInvalid
     */
    public function unit_cost_is_validated(string $unitCost, string $rule): void
    {
        Livewire::test(RecordSale::class)
            ->set('unitCost', $unitCost)
            ->call('save')
            ->assertHasErrors(['unitCost' => $rule]);
    }

    /** @test */
    public function can_not_create_a_sale_with_an_invalid_product_name(): void
    {
        $this->seed(ProductSeeder::class);

        $this->expectException(ModelNotFoundException::class);
        Livewire::test(RecordSale::class)
            ->set('quantity', 1)
            ->set('unitCost', '10.00')
            ->set('productName', 'invalid_product_name')
            ->call('save');
    }
}
