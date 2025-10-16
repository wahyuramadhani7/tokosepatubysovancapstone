@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">EDIT CATATAN PEMBELIAN</h1>

        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('purchase_notes.update', $purchaseNote->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Jenis Barang</label>
                        <select name="type" id="type" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="sneaker" {{ $purchaseNote->type == 'sneaker' ? 'selected' : '' }}>Sneaker</option>
                            <option value="apparel" {{ $purchaseNote->type == 'apparel' ? 'selected' : '' }}>Apparel</option>
                            <option value="aksesoris" {{ $purchaseNote->type == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                            <option value="tas" {{ $purchaseNote->type == 'tas' ? 'selected' : '' }}>Tas</option>
                            <option value="other" {{ !in_array($purchaseNote->type, ['sneaker', 'apparel', 'aksesoris', 'tas']) ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="custom-type-container" class="{{ in_array($purchaseNote->type, ['sneaker', 'apparel', 'aksesoris', 'tas']) ? 'hidden' : '' }}">
                        <label for="custom_type" class="block text-sm font-medium text-gray-700">Jenis Barang Lainnya</label>
                        <input type="text" name="custom_type" id="custom_type" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('custom_type', !in_array($purchaseNote->type, ['sneaker', 'apparel', 'aksesoris', 'tas | tas']) ? $purchaseNote->type : '') }}">
                        @error('custom_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('product_name', $purchaseNote->product_name) }}">
                        @error('product_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <input type="text" name="size" id="size" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('size', $purchaseNote->size) }}">
                        @error('size')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" name="color" id="color" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('color', $purchaseNote->color) }}">
                        @error('color')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="original_price" class="block text-sm font-medium text-gray-700">Harga Asli</label>
                        <input type="number" name="original_price" id="original_price" step="0.01" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('original_price', $purchaseNote->original_price) }}">
                        @error('original_price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-gray-700">Harga Diskon</label>
                        <input type="number" name="discount_price" id="discount_price" step="0.01" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('discount_price', $purchaseNote->discount_price) }}">
                        @error('discount_price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" min="1" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500" value="{{ old('quantity', $purchaseNote->quantity) }}">
                        @error('quantity')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('purchase_notes.index') }}" class="bg-gray-500 text-white font-medium text-sm rounded-lg px-4 py-2 hover:bg-gray-600 transition-colors">Kembali</a>
                    <button type="submit" class="bg-orange-500 text-black font-medium text-sm rounded-lg px-4 py-2 hover:bg-orange-600 transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const customTypeContainer = document.getElementById('custom-type-container');

    typeSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            customTypeContainer.classList.remove('hidden');
        } else {
            customTypeContainer.classList.add('hidden');
            document.getElementById('custom_type').value = '';
        }
    });
});
</script>
@endsection