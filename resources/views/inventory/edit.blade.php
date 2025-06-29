@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Form Edit Produk dengan Background Hitam -->
        <div class="bg-gray-900 shadow rounded-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-orange-500 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">EDIT PRODUK</h1>
            </div>

            <form action="{{ route('inventory.update', $product->id) }}" method="POST" id="edit-product-form">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-white mb-1">Brand</label>
                        <input type="text" name="brand" id="brand" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('brand') border-red-500 @enderror" value="{{ old('brand', explode(' ', $product->name)[0] ?? '') }}">
                        @error('brand')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-white mb-1">Model</label>
                        <input type="text" name="model" id="model" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('model') border-red-500 @enderror" value="{{ old('model', implode(' ', array_slice(explode(' ', $product->name), 1)) ?: 'Unknown') }}">
                        @error('model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-white mb-1">Warna</label>
                        <input type="text" name="color" id="color" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('color') border-red-500 @enderror" value="{{ old('color', $product->color) }}">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="relative">
                        <label for="selling_price" class="block text-sm font-medium text-white mb-1">Harga Jual</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border-0 bg-gray-200 text-gray-900 text-sm">Rp</span>
                            <input type="text" name="selling_price" id="selling_price" class="block w-full p-3 border-0 rounded-r-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('selling_price') border-red-500 @enderror" value="{{ old('selling_price', number_format($product->selling_price, 0, ',', '.')) }}">
                        </div>
                        @error('selling_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Diskon -->
                    <div class="relative">
                        <label for="discount_price" class="block text-sm font-medium text-white mb-1">Harga Diskon (Opsional)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border-0 bg-gray-200 text-gray-900 text-sm">Rp</span>
                            <input type="text" name="discount_price" id="discount_price" class="block w-full p-3 border-0 rounded-r-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('discount_price') border-red-500 @enderror" value="{{ old('discount_price', $product->discount_price ? number_format($product->discount_price, 0, ',', '.') : '') }}">
                        </div>
                        @error('discount_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-400 text-sm mt-1">Harga diskon harus lebih kecil atau sama dengan harga jual.</p>
                    </div>
                </div>

                <!-- Ukuran dan Stok -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-white mb-2">Ukuran dan Stok</label>
                    <div id="size-stock-container" class="space-y-4">
                        <div class="size-stock-group grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="sizes[0][size]" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('sizes.0.size') border-red-500 @enderror" placeholder="Ukuran (contoh: 41)" value="{{ old('sizes.0.size', $product->size) }}">
                                @error('sizes.0.size')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" name="sizes[0][stock]" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('sizes.0.stock') border-red-500 @enderror" placeholder="Jumlah Unit" value="{{ old('sizes.0.stock', $product->productUnits()->where('is_active', true)->count()) }}">
                                <button type="button" class="remove-size-stock bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                            </div>
                            @error('sizes.0.stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="button" id="add-size-stock" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Tambah Ukuran</button>
                </div>

                <!-- Tombol Simpan dan Kembali -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('inventory.index') }}" class="bg-gray-600 text-white px-5 py-2 rounded-lg hover:bg-gray-700">Kembali</a>
                    <button type="submit" class="bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-product-form');
    const sellingPriceInput = document.getElementById('selling_price');
    const discountPriceInput = document.getElementById('discount_price');
    const sizeStockContainer = document.getElementById('size-stock-container');
    const addSizeStockButton = document.getElementById('add-size-stock');
    let sizeStockIndex = 1;

    // Function to format number to Rupiah without trailing zeros
    function formatRupiah(value) {
        if (!value) return '';
        let cleanValue = value.replace(/\D/g, '');
        if (!cleanValue) return '';
        let number = parseFloat(cleanValue);
        return number.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).replace('Rp', '').trim();
    }

    // Function to parse Rupiah string to float
    function parseRupiah(value) {
        if (!value) return 0;
        let cleanValue = value.replace(/\D/g, '');
        return cleanValue ? parseFloat(cleanValue) : 0;
    }

    // Handle input for price fields
    [sellingPriceInput, discountPriceInput].forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value) {
                let cursorPosition = this.selectionStart;
                let oldLength = this.value.length;
                this.value = formatRupiah(value);
                let newLength = this.value.length;
                let cursorOffset = newLength - oldLength;
                this.setSelectionRange(cursorPosition + cursorOffset, cursorPosition + cursorOffset);
            } else {
                this.value = '';
            }
        });

        input.addEventListener('focus', function() {
            if (this.value) {
                this.value = formatRupiah(this.value.replace(/\D/g, ''));
            }
        });

        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = formatRupiah(this.value.replace(/\D/g, ''));
            }
        });
    });

    // Add new size and stock input fields
    addSizeStockButton.addEventListener('click', function() {
        const newGroup = document.createElement('div');
        newGroup.className = 'size-stock-group grid grid-cols-1 md:grid-cols-2 gap-4';
        newGroup.innerHTML = `
            <div>
                <input type="text" name="sizes[${sizeStockIndex}][size]" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500" placeholder="Ukuran (contoh: 41)">
            </div>
            <div class="flex items-center gap-2">
                <input type="number" name="sizes[${sizeStockIndex}][stock]" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500" placeholder="Jumlah Unit">
                <button type="button" class="remove-size-stock bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">Hapus</button>
            </div>
        `;
        sizeStockContainer.appendChild(newGroup);
        sizeStockIndex++;

        // Update remove buttons visibility
        updateRemoveButtons();
    });

    // Remove size and stock input fields
    sizeStockContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-size-stock')) {
            e.target.closest('.size-stock-group').remove();
            updateRemoveButtons();
        }
    });

    // Update visibility of remove buttons
    function updateRemoveButtons() {
        const groups = sizeStockContainer.querySelectorAll('.size-stock-group');
        groups.forEach((group, index) => {
            const removeButton = group.querySelector('.remove-size-stock');
            if (removeButton) {
                removeButton.classList.toggle('hidden', index === 0 && groups.length === 1);
            }
        });
    }

    // Clean values before form submission
    form.addEventListener('submit', function(e) {
        let sellingPrice = parseRupiah(sellingPriceInput.value);
        let discountPrice = parseRupiah(discountPriceInput.value);

        sellingPriceInput.value = sellingPrice.toFixed(0);
        discountPriceInput.value = discountPrice ? discountPrice.toFixed(0) : '';

        if (discountPrice > sellingPrice) {
            e.preventDefault();
            discountPriceInput.classList.add('border-red-500');
            const errorMessage = document.createElement('p');
            errorMessage.className = 'text-red-500 text-sm mt-1';
            errorMessage.textContent = 'Harga diskon tidak boleh lebih besar dari harga jual.';
            const existingError = discountPriceInput.nextElementSibling;
            if (existingError && existingError.tagName === 'P' && existingError.className.includes('text-red-500')) {
                existingError.remove();
            }
            discountPriceInput.parentElement.appendChild(errorMessage);
        }
    });

    // Real-time validation for discount price
    discountPriceInput.addEventListener('input', function() {
        const sellingPrice = parseRupiah(sellingPriceInput.value);
        const discountPrice = parseRupiah(this.value);
        const errorElement = this.nextElementSibling;

        if (errorElement && errorElement.tagName === 'P' && errorElement.className.includes('text-red-500')) {
            errorElement.remove();
        }

        if (discountPrice > sellingPrice) {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
    });
});
</script>
@endsection