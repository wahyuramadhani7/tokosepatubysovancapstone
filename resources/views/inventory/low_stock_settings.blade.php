@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">
            PENGATURAN STOK MENIPIS
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-800 rounded-lg p-6">
            <form action="{{ route('inventory.update_low_stock_threshold') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="threshold" class="block text-white font-medium mb-2">
                        Jumlah Minimum Stok (Low Stock Threshold)
                    </label>
                    <input type="number" name="threshold" id="threshold" 
                           value="{{ old('threshold', $lowStockThreshold) }}" 
                           min="1" max="100" required
                           class="w-full bg-white text-gray-900 rounded-lg py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-gray-400 text-sm mt-2">
                        Saat ini: <strong>{{ $lowStockThreshold }} unit</strong> → dianggap "stok menipis"
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-orange-500 text-black font-bold py-2.5 px-6 rounded-lg hover:bg-orange-600 transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('inventory.index') }}" class="text-orange-400 hover:underline">
                ← Kembali ke Inventory
            </a>
        </div>
    </div>
</div>
@endsection