<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\SystemProduct;
use App\Helper\MoneyHelper;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RecordSale extends Component
{
    #[Validate('required|numeric|min:1')]
    public ?int $quantity = null;

    #[Validate('required|decimal:0,2|min:0')]
    public string $unitCost = '';

    #[Validate('required')]
    public string $productName = SystemProduct::GoldCoffee->value;

    private SaleService $saleService;

    public function boot(SaleService $saleService): void
    {
        $this->saleService = $saleService;
    }

    public function save(): void
    {
        $this->validate();

        /** @var int $quantity */
        $quantity = $this->quantity;
        $intUnitCost = MoneyHelper::toMinorCurrencyInt($this->unitCost);

        $this->saleService->createSale($quantity, $intUnitCost, $this->productName);

        $this->reset();
        $this->dispatch('sales-updated');
    }

    /**
     * If unitCost is valid, format to 2dp on update
     */
    public function updatedUnitCost(): void
    {
        if ($this->unitCost === '') {
            return;
        }

        $this->validateOnly('unitCost');
        $this->unitCost = number_format(floatval($this->unitCost), 2, '.', '');
    }

    /**
     * @return string[]
     */
    #[Computed]
    public function productNames(): array
    {
        /** @var string[] $names */
        $names = Product::all('name')->pluck('name')->toArray();

        return $names;
    }

    public function render(): View|Factory
    {
        if ($this->quantity !== null && is_numeric($this->unitCost)) {
            $sellingPrice = $this->saleService->calculateSellingPrice(
                $this->quantity,
                MoneyHelper::toMinorCurrencyInt($this->unitCost),
                $this->productName
            );
        }

        $formattedSellingPrice = match ($sellingPrice ?? null) {
            null => 'N/A',
            default => '£' . number_format($sellingPrice / 100, 2),
        };

        return view('livewire.record-sale')->with([
            'sellingPrice' => $formattedSellingPrice,
        ]);
    }
}
