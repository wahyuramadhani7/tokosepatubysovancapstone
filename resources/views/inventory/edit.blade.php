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
                        <input type="text" name="model" id="model" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('model') border-red-500 @enderror" value="{{ old('model', implode(' ', array_slice(explode(' ', $product->name), 1)) ?? '') }}">
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

                    <!-- Ukuran -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-white mb-1">Ukuran</label>
                        <input type="text" name="size" id="size" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('size') border-red-500 @enderror" value="{{ old('size', $product->size) }}">
                        @error('size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-white mb-1">Stok</label>
                        <input type="number" name="stock" id="stock" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('stock') border-red-500 @enderror" value="{{ old('stock', $product->stock) }}">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-white mb-1">Harga Jual</label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('selling_price') border-red-500 @enderror" value="{{ old('selling_price', $product->selling_price) }}">
                        @error('selling_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Diskon -->
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-white mb-1">Harga Diskon (Opsional)</label>
                        <input type="number" step="0.01" name="discount_price" id="discount_price" class="block w-full p-3 border-0 rounded-lg bg-white text-gray-900 focus:ring-orange-500 focus:border-orange-500 @error('discount_price') border-red-500 @enderror" value="{{ old('discount_price', $product->discount_price) }}">
                        @error('discount_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-400 text-sm mt-1">Harga diskon harus lebih kecil atau sama dengan harga jual.</p>
                    </div>

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

        form.addEventListener('submit', function(e) {
            const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
            const discountPrice = parseFloat(discountPriceInput.value) || 0;

            if (discountPrice > sellingPrice) {
                e.preventDefault();
                discountPriceInput.classList.add('border-red-500');
                const errorMessage = document.createElement('p');
                errorMessage.className = 'text-red-500 text-sm mt-1';
                errorMessage.textContent = 'Harga diskon tidak boleh lebih besar dari harga jual.';
                const existingError = discountPriceInput.nextElementSibling;
                if (existingError && existingError.tagName === 'P') {
                    existingError.remove();
                }
                discountPriceInput.parentElement.appendChild(errorMessage);
            }
        });

        discountPriceInput.addEventListener('input', function() {
            const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
            const discountPrice = parseFloat(this.value) || 0;
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