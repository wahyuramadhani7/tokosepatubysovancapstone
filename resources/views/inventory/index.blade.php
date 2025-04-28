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
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z"></path>
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Total Produk</h3>
                    <p class="text-gray-600 text-lg">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Stok Menipis -->
            <div class="bg-gray-100 p-6 rounded-lg shadow flex items-center">
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z"></path>
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Stok Menipis</h3>
                    <p class="text-gray-600 text-lg">{{ $lowStockProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Total Stok -->
            <div class="bg-gray-100 p-6 rounded-lg shadow flex items-center">
                <svg class="h-10 w-10 text-orange-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5"></path>
                </svg>
                <div>
                    <h3 class="text-base font-semibold uppercase">Total Stok</h3>
                    <p class="text-gray-600 text-lg">{{ $totalStock ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Search Bar and Buttons -->
        <div class="flex justify-between items-center mb-4">
            <div class="relative w-1/3">
                <input type="text" placeholder="Search..." class="w-full p-2 border rounded-lg pl-10">
                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="space-x-2">
                <a href="{{ route('inventory.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">Tambah</a>
                <a href="{{ route('inventory.history') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Riwayat</a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products ?? [] as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->qr_code ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->size ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->selling_price ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection