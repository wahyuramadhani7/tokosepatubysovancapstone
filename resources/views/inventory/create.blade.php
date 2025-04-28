@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">TAMBAH PRODUK</h1>

        <!-- Form Tambah Produk -->
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                        <input type="text" name="brand" id="brand" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('brand') border-red-500 @enderror" value="{{ old('brand') }}">
                        @error('brand')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" name="model" id="model" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('model') border-red-500 @enderror" value="{{ old('model') }}">
                        @error('model')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" name="color" id="color" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('color') border-red-500 @enderror" value="{{ old('color') }}">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ukuran -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <input type="text" name="size" id="size" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('size') border-red-500 @enderror" value="{{ old('size') }}">
                        @error('size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" id="stock" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('stock') border-red-500 @enderror" value="{{ old('stock') }}">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" class="mt-1 block w-full p-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('selling_price') border-red-500 @enderror" value="{{ old('selling_price') }}">
                        @error('selling_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Simpan dan Kembali -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Kembali</a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection