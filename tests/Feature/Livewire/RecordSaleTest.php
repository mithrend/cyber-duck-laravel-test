<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\RecordSale;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\ProductSeeder;
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

        Livewire::test(RecordSale::class)
            ->set('quantity', 1)
            ->set('unitCost', '10.00')
            ->call('save');

        $this->assertEquals(1, Sale::count());
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
}
