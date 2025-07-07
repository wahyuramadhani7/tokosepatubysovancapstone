@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <div class="rounded-lg p-4 md:p-6 mb-6" style="background-color: #292929;">
            <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-white text-center">INVENTORY INFORMATION</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Total Produk</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalProducts ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Stok Menipis</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $lowStockProducts ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Total Unit</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalStock ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m-5 10V7m5 10h2a2 2 0 002-2V7a2 2 0 00-2-2h-2M7 7H5a2 2 0 00-2 2v6a2 2 0 002 2h2" />
                    </svg>
                    <div class="w-full">
                        <h3 class="text-sm md:text-base font-semibold uppercase">Jumlah Unit per Brand</h3>
                        <select id="brand-counts" class="w-full bg-white text-gray-600 text-sm md:text-base rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @forelse ($brandCounts ?? [] as $brand => $count)
                                <option value="{{ $brand }}">{{ $brand }} ({{ $count }} unit)</option>
                            @empty
                                <option value="">Tidak ada brand</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar, Add Product Button, and Stock Opname Button -->
        <div style="background-color: #292929;" class="flex flex-col md:flex-row justify-between items-center p-4 md:p-6 mb-6 rounded-lg gap-4 shadow-sm">
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <form action="{{ route('inventory.search') }}" method="GET" class="flex items-center w-full max-w-md">
                    <div class="relative flex-grow">
                        <input type="text" name="search" id="search-input" class="w-full bg-white text-black text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500" placeholder="Cari produk..." value="{{ $searchTerm ?? '' }}">
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="ml-2 bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors">Cari</button>
                </form>
                <div class="flex gap-3">
                    <a href="{{ route('inventory.create') }}" class="bg-white text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-gray-100 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </a>
                    <a href="{{ route('inventory.stock_opname') }}" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01M12 12h.01" />
                        </svg>
                        Stock Opname
                    </a>
                </div>
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
        <div class="shadow rounded-lg overflow-hidden hidden md:block p-4" style="background-color: #292929;">
            <div class="rounded-lg overflow-hidden">
                <!-- Table Headers -->
                <div class="grid grid-cols-9 gap-0">
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Produk</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Ukuran</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Warna</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Stok Sistem</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Stok Fisik</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Harga Jual</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Harga Diskon</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">QR Code</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Actions</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1" id="desktop-table-body">
                    @php
                        $currentGroup = '';
                    @endphp
                    @forelse ($products->filter(fn($product) => $product->product_units_count > 0) ?? [] as $index => $product)
                        @if ($currentGroup !== $product->name)
                            @if ($currentGroup !== '')
                                <div class="border-t border-gray-300 my-2"></div>
                            @endif
                            <div class="bg-orange-500 text-black font-semibold py-2 px-3">{{ $product->name }}</div>
                            @php
                                $currentGroup = $product->name;
                            @endphp
                        @endif
                        <div class="grid grid-cols-9 gap-0 items-center {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                            <div class="p-3 text-black">
                                <div class="flex items-center">
                                    <span>{{ $product->name ?? '-' }}</span>
                                    @if (in_array($product->id, $newProducts ?? []))
                                        <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>
                                    @elseif (in_array($product->id, $updatedProducts ?? []))
                                        <span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>
                                    @endif
                                </div>
                                @if(session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]))
                                    <p class="text-sm text-red-600 mt-1">{{ session('stock_mismatches')[$product->id]['message'] }}</p>
                                @endif
                            </div>
                            <div class="p-3 text-black text-center">{{ $product->size ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $product->color ?? '-' }}</div>
                            <div class="p-3 font-medium text-center {{ $product->product_units_count < 5 ? 'text-red-600' : 'text-black' }}">{{ $product->product_units_count ?? 0 }}</div>
                            <div class="p-3 font-medium text-center {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['difference']) ? (session('stock_mismatches')[$product->id]['difference'] < 0 ? 'text-red-600' : 'text-yellow-600') : 'text-black' }}">
                                {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['physical_stock']) ? session('stock_mismatches')[$product->id]['physical_stock'] : ($product->product_units_count ?? 0) }}
                            </div>
                            <div class="p-3 text-black text-right">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                            <div class="p-3 text-black text-right">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div>
                            <div class="p-3 text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                            </div>
                            <div class="p-3">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-600 hover:text-green-800 transition-colors" title="Cetak QR">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-6 text-center text-gray-500">
                            Tidak ada produk ditemukan dengan stok lebih dari 0.
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 flex justify-center" id="desktop-pagination">
                    @if(isset($products) && $products->hasPages())
                        {{ $products->links() }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4" id="mobile-cards">
            @php
                $currentGroup = '';
            @endphp
            @forelse ($products->filter(fn($product) => $product->product_units_count > 0) ?? [] as $product)
                @if ($currentGroup !== $product->name)
                    @if ($currentGroup !== '')
                        </div>
                    @endif
                    <div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg">{{ $product->name }}</div>
                    <div class="space-y-4">
                        @php
                            $currentGroup = $product->name;
                        @endphp
                @endif
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="mb-2 flex items-center">
                        <div>
                            <h3 class="font-medium text-gray-900 flex items-center">
                                {{ $product->name ?? '-' }}
                                @if (in_array($product->id, $newProducts ?? []))
                                    <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>
                                @elseif (in_array($product->id, $updatedProducts ?? []))
                                    <span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>
                                @endif
                            </h3>
                            <div class="text-sm text-gray-500">{{ $product->size ?? '-' }} | {{ $product->color ?? '-' }}</div>
                            @if(session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]))
                                <p class="text-sm text-red-600 mt-1">{{ session('stock_mismatches')[$product->id]['message'] }}</p>
                            @endif
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <div class="text-xs text-gray-500">Stok Sistem</div>
                            <div class="font-medium {{ $product->product_units_count < 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->product_units_count ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Stok Fisik</div>
                            <div class="font-medium {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['difference']) ? (session('stock_mismatches')[$product->id]['difference'] < 0 ? 'text-red-600' : 'text-yellow-600') : 'text-gray-900' }}">
                                {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['physical_stock']) ? session('stock_mismatches')[$product->id]['physical_stock'] : ($product->product_units_count ?? 0) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Harga Jual</div>
                            <div class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Harga Diskon</div>
                            <div class="font-medium text-gray-900">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-500 hover:text-green-800 transition-colors flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print QR
                        </a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-4">
                    Tidak ada produk ditemukan dengan stok lebih dari 0.
                </div>
            @endforelse
            @if ($currentGroup !== '')
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center" id="mobile-pagination">
            @if(isset($products) && $products->hasPages())
                {{ $products->links() }}
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    .grid-cols-9 {
        grid-template-columns: repeat(9, minmax(0, 1fr));
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(this.value.trim(), 1);
            }, 500);
        });
    }

    function performSearch(keyword, page) {
        if (keyword.length < 2) {
            showError('Kata kunci minimal 2 karakter.');
            return;
        }

        const url = new URL('{{ route('inventory.search') }}');
        url.searchParams.set('search', keyword);
        url.searchParams.set('page', page);

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            const desktopTableBody = document.getElementById('desktop-table-body');
            const mobileCards = document.getElementById('mobile-cards');
            const brandCountsSelect = document.getElementById('brand-counts');
            desktopTableBody.innerHTML = '';
            mobileCards.innerHTML = '';
            brandCountsSelect.innerHTML = '';

            if (data.error) {
                showError(data.message);
                return;
            }

            // Update brand counts dropdown
            if (data.brandCounts && Object.keys(data.brandCounts).length > 0) {
                let brandHtml = '';
                for (const [brand, count] of Object.entries(data.brandCounts)) {
                    brandHtml += `<option value="${brand}">${brand} (${count} unit)</option>`;
                }
                brandCountsSelect.innerHTML = brandHtml;
            } else {
                brandCountsSelect.innerHTML = '<option value="">Tidak ada brand</option>';
            }

            if (!data.products.length) {
                desktopTableBody.innerHTML = '<div class="bg-white p-6 text-center text-gray-500">Tidak ada produk ditemukan dengan stok lebih dari 0.</div>';
                mobileCards.innerHTML = '<div class="text-center text-gray-500 p-4">Tidak ada produk ditemukan dengan stok lebih dari 0.</div>';
                updatePagination(data.pagination, keyword);
                return;
            }

            let currentGroup = '';
            let mobileGroupHtml = '';

            data.products.forEach((product, index) => {
                const stockValue = product.stock || 0;
                if (stockValue <= 0) return; // Skip products with stock <= 0
                const mismatch = @json(session('stock_mismatches', []))[product.id] || {};
                const physicalStock = mismatch.physical_stock || stockValue;
                const stockDifference = mismatch.difference || 0;
                const mismatchMessage = mismatch.message || '';
                const isNew = @json($newProducts ?? []).includes(product.id);
                const isUpdated = @json($updatedProducts ?? []).includes(product.id);

                // Desktop table row
                let desktopRow = '';
                if (currentGroup !== product.name) {
                    if (currentGroup !== '') {
                        desktopRow += '<div class="border-t border-gray-300 my-2"></div>';
                    }
                    desktopRow += `<div class="bg-orange-500 text-black font-semibold py-2 px-3">${product.name}</div>`;
                    currentGroup = product.name;
                }

                desktopRow += `
                    <div class="grid grid-cols-9 gap-0 items-center ${index % 2 === 0 ? 'bg-white' : 'bg-gray-200'}">
                        <div class="p-3 text-black">
                            <div class="flex items-center">
                                <span>${product.name || '-'}</span>
                                ${isNew ? '<span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>' : isUpdated ? '<span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>' : ''}
                            </div>
                            ${mismatchMessage ? `<p class="text-sm text-red-600 mt-1">${mismatchMessage}</p>` : ''}
                        </div>
                        <div class="p-3 text-black text-center">${product.size || '-'}</div>
                        <div class="p-3 text-black text-center">${product.color || '-'}</div>
                        <div class="p-3 font-medium text-center ${stockValue < 5 ? 'text-red-600' : 'text-black'}">${stockValue}</div>
                        <div class="p-3 font-medium text-center ${stockDifference < 0 ? 'text-red-600' : stockDifference > 0 ? 'text-yellow-600' : 'text-black'}">${physicalStock}</div>
                        <div class="p-3 text-black text-right">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div>
                        <div class="p-3 text-black text-right">${product.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(product.discount_price) : '-'}</div>
                        <div class="p-3 text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route('inventory.show', ':id') }}'.replace(':id', product.id))}" alt="QR Code for ${product.name || '-'}" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                        </div>
                        <div class="p-3">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-600 hover:text-green-800 transition-colors" title="Cetak QR">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                                <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>`;

                // Mobile card
                if (currentGroup !== product.name) {
                    if (mobileGroupHtml !== '') {
                        mobileGroupHtml += '</div>';
                    }
                    mobileGroupHtml += `<div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg">${product.name}</div><div class="space-y-4">`;
                    currentGroup = product.name;
                }

                mobileGroupHtml += `
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="mb-2 flex items-center">
                            <div>
                                <h3 class="font-medium text-gray-900 flex items-center">
                                    ${product.name || '-'}
                                    ${isNew ? '<span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>' : isUpdated ? '<span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>' : ''}
                                </h3>
                                <div class="text-sm text-gray-500">${product.size || '-'} | ${product.color || '-'}</div>
                                ${mismatchMessage ? `<p class="text-sm text-red-600 mt-1">${mismatchMessage}</p>` : ''}
                            </div>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route('inventory.show', ':id') }}'.replace(':id', product.id))}" alt="QR Code for ${product.name || '-'}" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div>
                                <div class="text-xs text-gray-500">Stok Sistem</div>
                                <div class="font-medium ${stockValue < 5 ? 'text-red-600' : 'text-gray-900'}">${stockValue}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Stok Fisik</div>
                                <div class="font-medium ${stockDifference < 0 ? 'text-red-600' : stockDifference > 0 ? 'text-yellow-600' : 'text-gray-900'}">${physicalStock}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Harga Jual</div>
                                <div class="font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Harga Diskon</div>
                                <div class="font-medium text-gray-900">${product.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(product.discount_price) : '-'}</div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                            <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-500 hover:text-green-800 transition-colors flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print QR
                            </a>
                            <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>`;

                desktopTableBody.insertAdjacentHTML('beforeend', desktopRow);
            });

            if (mobileGroupHtml !== '') {
                mobileGroupHtml += '</div>';
                mobileCards.insertAdjacentHTML('beforeend', mobileGroupHtml);
            }

            updatePagination(data.pagination, keyword);
        })
        .catch(error => {
            showError('Terjadi kesalahan saat mencari produk.');
        });
    }

    function showError(message) {
        const desktopTableBody = document.getElementById('desktop-table-body');
        const mobileCards = document.getElementById('mobile-cards');
        const brandCountsSelect = document.getElementById('brand-counts');
        const errorHtml = `<div class="bg-white p-6 text-center text-red-500">${message}</div>`;
        desktopTableBody.innerHTML = errorHtml;
        mobileCards.innerHTML = errorHtml;
        brandCountsSelect.innerHTML = '<option value="">Tidak ada brand</option>';
        document.getElementById('desktop-pagination').innerHTML = '';
        document.getElementById('mobile-pagination').innerHTML = '';
    }

    function updatePagination(pagination, keyword) {
        const desktopPagination = document.getElementById('desktop-pagination');
        const mobilePagination = document.getElementById('mobile-pagination');
        let html = '';

        if (pagination && pagination.last_page > 1) {
            html = '<nav class="pagination flex space-x-2">';
            for (let i = 1; i <= pagination.last_page; i++) {
                html += `<a href="#" class="pagination-link px-3 py-1 rounded ${i === pagination.current_page ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'}" data-page="${i}">${i}</a>`;
            }
            html += '</nav>';

            desktopPagination.innerHTML = html;
            mobilePagination.innerHTML = html;

            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    performSearch(keyword, this.getAttribute('data-page'));
                });
            });
        } else {
            desktopPagination.innerHTML = '';
            mobilePagination.innerHTML = '';
        }
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection