@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">VERIFIKASI STOK</h1>

        <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <h2 class="text-lg md:text-xl font-semibold mb-4">Verifikasi Stok untuk {{ $product->name }}</h2>
            
            <div class="mb-4">
                <p><strong>Nama Produk:</strong> {{ $product->name }}</p>
                <p><strong>Ukuran:</strong> {{ $product->size }}</p>
                <p><strong>Warna:</strong> {{ $product->color }}</p>
                <p><strong>Stok Sistem:</strong> {{ $product->stock }}</p>
            </div>

            <form action="{{ route('inventory.updatePhysicalStock', $product->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="physical_stock" class="block text-sm font-medium text-gray-700">Stok Fisik</label>
                    <input type="number" name="physical_stock" id="physical_stock" min="0" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                    @error('physical_stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('inventory.index') }}" class="bg-gray-200 text-black px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
                    <button type="submit" class="bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">Simpan Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@