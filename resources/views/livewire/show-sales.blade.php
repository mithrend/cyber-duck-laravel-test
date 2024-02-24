<div>
    <table class="table-fixed w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Quantity</th>
                <th scope="col" class="px-6 py-3 text-right">Unit Cost</th>
                <th scope="col" class="px-6 py-3 text-right">Selling Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr class="bg-white border-b">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ $sale->quantity }}
                </td>
                <td class="px-6 py-4 text-right font-medium text-gray-900 whitespace-nowrap">
                    £{{ number_format($sale->unit_cost / 100, 2) }}
                </td>
                <td class="px-6 py-4 text-right font-medium text-gray-900 whitespace-nowrap">
                    £{{ number_format($sale->selling_price / 100, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
