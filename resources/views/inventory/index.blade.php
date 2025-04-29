@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <h2 class="text-xl font-semibold mb-4">INVENTORY INFORMATION</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <!-- Total Produk -->
            <div class="bg-gray-100 p-6 rounded-lg shadow flex items-center">
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Total Produk</h3>
                    <p class="text-gray-600 text-lg">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Stok Menipis -->
            <div class="bg-gray-100 p-6 rounded-lg shadow flex items-center">
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Stok Menipis</h3>
                    <p class="text-gray-600 text-lg">{{ $lowStockProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Total Stok -->
            <div class="bg-gray-100 p-6 rounded-lg shadow flex items-center">
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Total Stok</h3>
                    <p class="text-gray-600 text-lg">{{ $totalStock ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Search Bar and Buttons -->
        <div class="flex justify-between items-center mb-4">
            <form action="{{ route('inventory.search') }}" method="GET" class="relative w-full sm:w-1/3">
                <input type="text" name="search" placeholder="Search..." class="w-full p-2 border rounded-lg pl-10">
                <button type="submit" class="absolute left-3 top-3 h-5 w-5 text-gray-500">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <div class="space-x-2 ml-4">
                <a href="{{ route('inventory.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">Tambah</a>
                @if(Route::has('inventory.history'))
                    <a href="{{ route('inventory.history') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Riwayat</a>
                @endif
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products ?? [] as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" class="h-16 w-16">
                                    <!-- Untuk debugging -->
                                    <div class="text-xs">{{ asset('storage/' . $product->qr_code) }}</div>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->size ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->color ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection