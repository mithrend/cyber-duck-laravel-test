<form wire:submit="save" class="flex gap-4">
    <div class="flex-auto flex flex-col w-1/4">
        <label for="productName" class="mb-2 font-medium text-gray-900">Quantity</label>
        <select id="productName" type="number" wire:model.blur="productName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
            @foreach ($this->productNames() as $productName)
            <option value="{{ $productName }}">{{ $productName }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-auto flex flex-col w-1/4">
        <label for="quantity" class="mb-2 font-medium text-gray-900">Quantity</label>
        <input id="quantity" type="number" wire:model.blur="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
        <div>
            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="flex-auto flex flex-col w-1/4">
        <label for="unitCost" class="mb-2 font-medium text-gray-900">Unit Cost (£)</label>
        <input id="unitCost" x-mask:dynamic="$money($input, '.', '')" wire:model.blur="unitCost" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
        <div>
            @error('unitCost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="flex-auto w-1/4">
        <div class="mb-2 font-medium text-gray-900">Selling Price</div>
        <div class="mt-4 font-medium text-gray-900">{{ $sellingPrice }}</div>
    </div>
    <div class="flex-auto w-1/4">
        <button type="submit" class="w-full mt-7 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            Record Sale
        </button>
    </div>
</form>
