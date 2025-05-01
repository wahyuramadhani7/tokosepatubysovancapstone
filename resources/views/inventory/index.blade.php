@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4">INVENTORY INFORMATION</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-6">
            <!-- Total Produk -->
            <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                </svg>
                <div>
                    <h3 class="text-sm md:text-base font-semibold uppercase">Total Produk</h3>
                    <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Stok Menipis -->
            <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <div>
                    <h3 class="text-sm md:text-base font-semibold uppercase">Stok Menipis</h3>
                    <p class="text-gray-600 text-base md:text-lg font-medium">{{ $lowStockProducts ?? 0 }}</p>
                </div>
            </div>
            <!-- Total Stok -->
            <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                </svg>
                <div>
                    <h3 class="text-sm md:text-base font-semibold uppercase">Total Stok</h3>
                    <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalStock ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Search Bar and Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <form action="{{ route('inventory.search') }}" method="GET" class="relative w-full sm:w-1/2 lg:w-1/3">
                <input type="text" name="search" placeholder="Search..." class="w-full p-2 border rounded-lg pl-10 focus:ring focus:ring-orange-200 focus:border-orange-500 transition-all">
                <button type="submit" class="absolute left-3 top-3 h-4 w-4 text-gray-500">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <div class="flex flex-wrap gap-2 sm:ml-4">
                <a href="{{ route('inventory.create') }}" class="bg-orange-500 text-white px-3 py-2 rounded-lg hover:bg-orange-600 transition-colors text-sm md:text-base flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </a>
                @if(Route::has('inventory.history'))
                    <a href="{{ route('inventory.history') }}" class="bg-gray-500 text-white px-3 py-2 rounded-lg hover:bg-gray-600 transition-colors text-sm md:text-base flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat
                    </a>
                @endif
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table - Desktop version -->
        <div class="bg-white shadow rounded-lg overflow-hidden hidden md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warna</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products ?? [] as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                @if($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" class="h-12 w-12">
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $product->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $product->size ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $product->color ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium {{ $product->stock < 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->stock ?? 0 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex space-x-2">
                                    <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards - Mobile version -->
        <div class="md:hidden space-y-4">
            @forelse ($products ?? [] as $product)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $product->name ?? '-' }}</h3>
                            <div class="text-sm text-gray-500">{{ $product->size ?? '-' }} | {{ $product->color ?? '-' }}</div>
                        </div>
                        @if($product->qr_code)
                            <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" class="h-12 w-12">
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <div class="text-xs text-gray-500">Stok</div>
                            <div class="font-medium {{ $product->stock < 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->stock ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Harga Jual</div>
                            <div class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                    Tidak ada produk ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* Optional: Add some animation for the alerts */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>

<script>
    // Optional: Add functionality to close alerts after a few seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    });
</script>
@endsection