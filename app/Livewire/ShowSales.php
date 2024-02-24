<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Sale;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowSales extends Component
{
    #[On('sales-updated')]
    public function render(): View|Factory
    {
        return view('livewire.show-sales')->with([
            'sales' => Sale::all()->sortByDesc('created_at'),
        ]);
    }
}
