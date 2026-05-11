<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Package Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $package->name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Storage --}}
    <div>
        <label for="storage_gb" class="block text-sm font-medium text-gray-700">Storage (GB)</label>
        <input type="number" name="storage_gb" id="storage_gb" value="{{ old('storage_gb', $package->storage_gb ?? 1) }}" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
        @error('storage_gb')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Price Monthly --}}
    <div>
        <label for="price_monthly" class="block text-sm font-medium text-gray-700">Price Monthly (USD Base)</label>
        <input type="number" step="0.01" name="price_monthly" id="price_monthly" value="{{ old('price_monthly', $package->price_monthly ?? 0.00) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
        @error('price_monthly')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Price Yearly --}}
    <div>
        <label for="price_yearly" class="block text-sm font-medium text-gray-700">Price Yearly (USD Base)</label>
        <input type="number" step="0.01" name="price_yearly" id="price_yearly" value="{{ old('price_yearly', $package->price_yearly ?? 0.00) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
        @error('price_yearly')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Description / Key Features</label>
        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">{{ old('description', $package->description ?? '') }}</textarea>
        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Visibility --}}
    <div class="flex items-center space-x-2">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $package->is_visible ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
        <label for="is_visible" class="text-sm font-medium text-gray-700">Visible to Public</label>
        @error('is_visible')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Sort Order --}}
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
        @error('sort_order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>
