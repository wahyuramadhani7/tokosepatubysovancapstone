@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <h1 class="text-xl md:text-2xl font-bold mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <div class="bg-gray-800 rounded-lg p-4 md:p-6 mb-6">
            <h2 class="text-lg md:text-xl font-semibold mb-4 text-white text-center">INVENTORY INFORMATION</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Stok Menipis -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase text-gray-700">Stok Menipis</h3>
                        <p id="low-stock-count" class="text-gray-900 text-base md:text-lg font-medium">{{ $lowStockProducts ?? 0 }}</p>
                    </div>
                </div>

                <!-- Total Unit -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase text-gray-700">Total Unit</h3>
                        <p id="total-stock" class="text-gray-900 text-base md:text-lg font-medium">{{ $totalStock ?? 0 }}</p>
                    </div>
                </div>

                <!-- Jumlah Unit per Brand -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m-5 10V7m5 10h2a2 2 0 002-2V7a2 2 0 00-2-2h-2M7 7H5a2 2 0 00-2 2v6a2 2 0 002 2h2" />
                    </svg>
                    <div class="w-full">
                        <h3 class="text-sm md:text-base font-semibold uppercase text-gray-700 mb-1.5">
                            Jumlah Unit per Brand
                            @if(request('brand'))
                                <span class="text-xs normal-case text-orange-600 ml-2">(aktif: {{ ucfirst(request('brand')) }})</span>
                            @endif
                        </h3>
                        <select 
                            id="brand-counts"
                            onchange="if(this.value) { window.location.href = '{{ route('inventory.search') }}?brand=' + encodeURIComponent(this.value); }"
                            class="w-full bg-white text-gray-900 text-sm md:text-base rounded-md py-2.5 px-4 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 cursor-pointer transition-all hover:border-orange-400 shadow-sm"
                        >
                            <option value="" disabled {{ !request('brand') ? 'selected' : '' }}>Pilih brand untuk filter...</option>
                            
                            @forelse ($allBrandCounts ?? [] as $brand => $count)
                                <option value="{{ $brand }}"
                                        {{ request('brand') === $brand ? 'selected' : '' }}>
                                    {{ ucfirst(strtolower($brand)) }} ({{ number_format($count, 0, ',', '.') }} unit)
                                </option>
                            @empty
                                <option value="">Tidak ada data brand tersedia</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar and Filters -->
        <div class="bg-gray-800 p-4 md:p-6 mb-6 rounded-lg shadow-sm">
            @if(request('brand') || $searchTerm || $sizeTerm || $lowStockFilter)
                <div class="mb-4 text-sm text-orange-300 flex flex-wrap items-center gap-2">
                    Filter aktif: 
                    @if(request('brand'))
                        Brand <strong>{{ ucfirst(request('brand')) }}</strong>
                    @endif
                    @if($searchTerm)
                        @if(request('brand')) | @endif
                        Cari: <strong>{{ $searchTerm }}</strong>
                    @endif
                    @if($sizeTerm)
                        @if(request('brand') || $searchTerm) | @endif
                        Ukuran: <strong>{{ $sizeTerm }}</strong>
                    @endif
                    @if($lowStockFilter)
                        @if(request('brand') || $searchTerm || $sizeTerm) | @endif
                        Stok Menipis
                    @endif
                    <a href="{{ route('inventory.index') }}" class="ml-3 text-gray-400 hover:text-white underline text-xs">(reset semua filter)</a>
                </div>
            @endif

            <form id="search-form" action="{{ route('inventory.search') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full">
                <div class="relative flex-grow">
                    <input type="text" name="search" id="search-input" class="w-full bg-white text-gray-900 text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500 border border-gray-300" placeholder="Cari brand / model / warna..." value="{{ $searchTerm ?? '' }}">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="relative flex-grow">
                    <input type="text" name="size" id="size-input" class="w-full bg-white text-gray-900 text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500 border border-gray-300" placeholder="Cari ukuran..." value="{{ $sizeTerm ?? '' }}">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex items-center whitespace-nowrap">
                    <input type="checkbox" id="low-stock-filter" name="low_stock" value="1" class="mr-2 h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded" {{ $lowStockFilter ? 'checked' : '' }}>
                    <label for="low-stock-filter" class="text-sm md:text-base text-white">Stok Menipis</label>
                </div>
                <button type="submit" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-6 py-2.5 hover:bg-orange-600 transition-colors w-full md:w-auto">Cari</button>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-800 p-4 md:p-6 mb-6 rounded-lg shadow-sm">
            <div class="flex flex-col md:flex-row gap-3 flex-wrap">
                <a href="{{ route('inventory.create') }}" class="bg-white text-black font-medium rounded-lg px-5 py-3 hover:bg-gray-100 transition-colors flex items-center justify-center flex-1 md:flex-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
                </a>
                <a href="{{ route('inventory.stock_opname') }}" class="bg-orange-500 text-black font-medium rounded-lg px-5 py-3 hover:bg-orange-600 transition-colors flex items-center justify-center flex-1 md:flex-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Stock Opname
                </a>
                <a href="{{ route('inventory.history') }}" class="bg-gray-600 text-white font-medium rounded-lg px-5 py-3 hover:bg-gray-700 transition-colors flex items-center justify-center flex-1 md:flex-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Produk
                </a>
                <a href="{{ route('purchase_notes.index') }}" class="bg-gray-600 text-white font-medium rounded-lg px-5 py-3 hover:bg-gray-700 transition-colors flex items-center justify-center flex-1 md:flex-none">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01M12 12h.01" />
                    </svg>
                    Catatan Barang Masuk
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-green-700" onclick="this.parentElement.remove()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-red-700" onclick="this.parentElement.remove()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table - Desktop version -->
        <div class="shadow rounded-lg overflow-hidden hidden md:block bg-gray-800 p-4 mb-6">
            <div class="rounded-lg overflow-hidden">
                <!-- Table Headers -->
                <div class="grid grid-cols-10 gap-0 bg-orange-500 text-black font-medium uppercase text-sm">
                    <div class="py-3 px-4 text-center">No</div>
                    <div class="py-3 px-4 text-center">Brand</div>
                    <div class="py-3 px-4 text-center">Model</div>
                    <div class="py-3 px-4 text-center">Ukuran</div>
                    <div class="py-3 px-4 text-center">Warna</div>
                    <div class="py-3 px-4 text-center">Stok Sistem</div>
                    <div class="py-3 px-4 text-center">Stok Fisik</div>
                    <div class="py-3 px-4 text-center">Harga Jual</div>
                    <div class="py-3 px-4 text-center">Diskon</div>
                    <div class="py-3 px-4 text-center">Aksi</div>
                </div>

                <!-- Table Body -->
                <div class="mt-1" id="desktop-table-body">
                    @php
                        $currentGroup = '';
                        $brandNames = Cache::get(auth()->check() ? 'user_' . auth()->id() . '_brand_names' : 'user_guest_brand_names', []);
                    @endphp
                    @forelse ($products->filter(fn($product) => $product->product_units_count > 0) ?? [] as $product)
                        @php
                            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';
                            $model = trim(str_replace($brand, '', $product->name));
                            $groupKey = $brand . ' ' . $model;
                            $rowNumber = ($products->currentPage() - 1) * $products->perPage() + $loop->iteration;
                        @endphp
                        @if ($currentGroup !== $groupKey)
                            @if ($currentGroup !== '')
                                <div class="border-t border-gray-300 my-2"></div>
                            @endif
                            <div class="bg-orange-500 text-black font-semibold py-2 px-3 uppercase-text">{{ strtoupper($brand) }} {{ strtoupper($model) }}</div>
                            @php $currentGroup = $groupKey; @endphp
                        @endif
                        <div class="grid grid-cols-10 gap-0 items-center {{ $loop->iteration % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <div class="p-3 text-black text-center">{{ $rowNumber }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $brand }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $model }}</div>
                            <div class="p-3 text-black text-center">{{ $product->size ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $product->color ?? '-' }}</div>
                            <div class="p-3 font-medium text-center {{ $product->product_units_count < ($lowStockThreshold ?? 5) ? 'text-red-600' : 'text-black' }}">{{ $product->product_units_count ?? 0 }}</div>
                            <div class="p-3 font-medium text-center {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['difference']) ? (session('stock_mismatches')[$product->id]['difference'] < 0 ? 'text-red-600' : 'text-yellow-600') : 'text-black' }}">
                                {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['physical_stock']) ? session('stock_mismatches')[$product->id]['physical_stock'] : ($product->product_units_count ?? 0) }}
                            </div>
                            <div class="p-3 text-black text-right">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                            <div class="p-3 text-black text-right">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div>
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

                <!-- Pagination Desktop -->
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
                $brandNames = Cache::get(auth()->check() ? 'user_' . auth()->id() . '_brand_names' : 'user_guest_brand_names', []);
            @endphp
            @forelse ($products->filter(fn($product) => $product->product_units_count > 0) ?? [] as $product)
                @php
                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0] ?? 'Unknown';
                    $model = trim(str_replace($brand, '', $product->name));
                    $groupKey = $brand . ' ' . $model;
                    $rowNumber = ($products->currentPage() - 1) * $products->perPage() + $loop->iteration;
                @endphp
                @if ($currentGroup !== $groupKey)
                    @if ($currentGroup !== '')
                        </div>
                    @endif
                    <div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg uppercase-text">{{ strtoupper($brand) }} {{ strtoupper($model) }}</div>
                    <div class="space-y-4">
                        @php $currentGroup = $groupKey; @endphp
                @endif
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="mb-2 flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">No: {{ $rowNumber }}</h3>
                            <div class="text-lg font-semibold uppercase-text">{{ $brand }}</div>
                            <div class="text-base uppercase-text text-gray-700">{{ $model }}</div>
                            <div class="text-sm text-gray-500 mt-1">{{ $product->size ?? '-' }} | <span class="uppercase-text">{{ $product->color ?? '-' }}</span></div>
                            @if(session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]))
                                <p class="text-sm text-red-600 mt-1">{{ session('stock_mismatches')[$product->id]['message'] }}</p>
                            @endif
                            @if (in_array($product->id, $newProducts ?? []))
                                <span class="inline-block mt-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>
                            @elseif (in_array($product->id, $updatedProducts ?? []))
                                <span class="inline-block mt-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>
                            @endif
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code" class="h-20 w-20" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                        <div>
                            <div class="text-gray-500">Stok Sistem</div>
                            <div class="font-medium {{ $product->product_units_count < ($lowStockThreshold ?? 5) ? 'text-red-600' : 'text-gray-900' }}">{{ $product->product_units_count ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Stok Fisik</div>
                            <div class="font-medium {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['difference']) ? (session('stock_mismatches')[$product->id]['difference'] < 0 ? 'text-red-600' : 'text-yellow-600') : 'text-gray-900' }}">
                                {{ session('stock_mismatches') && isset(session('stock_mismatches')[$product->id]) && isset(session('stock_mismatches')[$product->id]['physical_stock']) ? session('stock_mismatches')[$product->id]['physical_stock'] : ($product->product_units_count ?? 0) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-500">Harga Jual</div>
                            <div class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Harga Diskon</div>
                            <div class="font-medium text-gray-900">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-600 hover:text-green-800 flex items-center text-sm">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print QR
                        </a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 flex items-center text-sm">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
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

        <!-- Pagination Mobile -->
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
    .grid-cols-10 {
        grid-template-columns: repeat(10, minmax(0, 1fr));
    }
    .uppercase-text {
        text-transform: uppercase;
    }
    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .pagination a {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        color: #2d3748;
        text-decoration: none;
    }
    .pagination a:hover {
        background-color: #edf2f7;
    }
    .pagination .active {
        background-color: #f97316;
        color: #fff;
        border-color: #f97316;
    }
    .pagination .disabled {
        color: #a0aec0;
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const sizeInput = document.getElementById('size-input');
    const lowStockFilter = document.getElementById('low-stock-filter');
    const brandSelect = document.getElementById('brand-counts');
    let debounceTimer;

    if (searchInput && sizeInput && lowStockFilter) {
        const inputs = [searchInput, sizeInput, lowStockFilter];
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performSearch(
                        searchInput.value.trim(),
                        sizeInput.value.trim(),
                        lowStockFilter.checked,
                        1
                    );
                }, 500);
            });
        });
    }

    function performSearch(searchKeyword, sizeKeyword, lowStock, page) {
        const url = new URL('{{ route('inventory.search') }}');

        // Pertahankan filter brand yang sedang aktif
        const currentBrand = new URLSearchParams(window.location.search).get('brand');
        if (currentBrand) {
            url.searchParams.set('brand', currentBrand);
        }

        if (searchKeyword) url.searchParams.set('search', searchKeyword);
        if (sizeKeyword) url.searchParams.set('size', sizeKeyword);
        if (lowStock) url.searchParams.set('low_stock', '1');
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
            const lowStockCount = document.getElementById('low-stock-count');
            const totalStock = document.getElementById('total-stock');

            desktopTableBody.innerHTML = '';
            mobileCards.innerHTML = '';
            lowStockCount.textContent = data.lowStockProducts ?? 0;
            totalStock.textContent = data.totalStock ?? 0;

            if (data.error) {
                showError(data.message);
                return;
            }

            // Update dropdown brand counts (jika ada perubahan)
            if (data.allBrandCounts && Object.keys(data.allBrandCounts).length > 0) {
                let optionsHtml = '<option value="" disabled>Pilih brand untuk filter...</option>';
                Object.keys(data.allBrandCounts)
                    .sort((a, b) => data.allBrandCounts[b] - data.allBrandCounts[a])
                    .forEach(brand => {
                        const selected = (new URLSearchParams(window.location.search).get('brand') === brand) ? ' selected' : '';
                        optionsHtml += `<option value="${brand}"${selected}>${brand.charAt(0).toUpperCase() + brand.slice(1).toLowerCase()} (${data.allBrandCounts[brand]} unit)</option>`;
                    });
                brandSelect.innerHTML = optionsHtml;
            }

            if (!data.products || data.products.length === 0) {
                desktopTableBody.innerHTML = '<div class="bg-white p-6 text-center text-gray-500">Tidak ada produk ditemukan.</div>';
                mobileCards.innerHTML = '<div class="text-center text-gray-500 p-4">Tidak ada produk ditemukan.</div>';
                updatePagination(data.pagination);
                return;
            }

            let currentGroup = '';
            let desktopHtml = '';
            let mobileHtml = '';

            data.products.forEach((product, index) => {
                const stock = product.stock || 0;
                if (stock <= 0) return;

                const brand = product.brand || 'Unknown';
                const model = product.model || '';
                const groupKey = brand + ' ' + model;
                const rowNumber = (data.pagination.current_page - 1) * data.pagination.per_page + index + 1;

                const mismatch = @json(session('stock_mismatches') ?? [])[product.id] || {};
                const physicalStock = mismatch.physical_stock ?? stock;
                const difference = mismatch.difference ?? 0;
                const message = mismatch.message || '';
                const isNew = @json($newProducts ?? []).includes(product.id);
                const isUpdated = @json($updatedProducts ?? []).includes(product.id);

                // Desktop Table
                if (currentGroup !== groupKey) {
                    if (currentGroup !== '') desktopHtml += '<div class="border-t border-gray-300 my-2"></div>';
                    desktopHtml += `<div class="bg-orange-500 text-black font-semibold py-2 px-3 uppercase-text">${brand.toUpperCase()} ${model.toUpperCase()}</div>`;
                    currentGroup = groupKey;
                }

                desktopHtml += `
                    <div class="grid grid-cols-10 gap-0 items-center ${index % 2 === 0 ? 'bg-white' : 'bg-gray-100'}">
                        <div class="p-3 text-black text-center">${rowNumber}</div>
                        <div class="p-3 text-black text-center uppercase-text">${brand}</div>
                        <div class="p-3 text-black text-center uppercase-text">${model}</div>
                        <div class="p-3 text-black text-center">${product.size || '-'}</div>
                        <div class="p-3 text-black text-center uppercase-text">${product.color || '-'}</div>
                        <div class="p-3 font-medium text-center ${stock < {{ $lowStockThreshold ?? 5 }} ? 'text-red-600' : 'text-black'}">${stock}</div>
                        <div class="p-3 font-medium text-center ${difference < 0 ? 'text-red-600' : difference > 0 ? 'text-yellow-600' : 'text-black'}">${physicalStock}</div>
                        <div class="p-3 text-black text-right">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div>
                        <div class="p-3 text-black text-right">${product.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(product.discount_price) : '-'}</div>
                        <div class="p-3">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-600 hover:text-green-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>`;

                // Mobile Card
                if (currentGroup !== groupKey) {
                    if (currentGroup !== '') mobileHtml += '</div>';
                    mobileHtml += `<div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg uppercase-text">${brand.toUpperCase()} ${model.toUpperCase()}</div><div class="space-y-4">`;
                    currentGroup = groupKey;
                }

                mobileHtml += `
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-900">No: ${rowNumber}</h3>
                                <div class="text-lg font-semibold uppercase-text">${brand}</div>
                                <div class="text-base uppercase-text text-gray-700">${model}</div>
                                <div class="text-sm text-gray-500 mt-1">${product.size || '-'} | <span class="uppercase-text">${product.color || '-'}</span></div>
                                ${message ? `<p class="text-sm text-red-600 mt-1">${message}</p>` : ''}
                                ${isNew ? '<span class="inline-block mt-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>' : ''}
                                ${isUpdated ? '<span class="inline-block mt-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>' : ''}
                            </div>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${encodeURIComponent('{{ route('inventory.show', ':id') }}'.replace(':id', product.id))}" alt="QR Code" class="h-20 w-20" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                            <div><div class="text-gray-500">Stok Sistem</div><div class="font-medium ${stock < {{ $lowStockThreshold ?? 5 }} ? 'text-red-600' : 'text-gray-900'}">${stock}</div></div>
                            <div><div class="text-gray-500">Stok Fisik</div><div class="font-medium ${difference < 0 ? 'text-red-600' : difference > 0 ? 'text-yellow-600' : 'text-gray-900'}">${physicalStock}</div></div>
                            <div><div class="text-gray-500">Harga Jual</div><div class="font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div></div>
                            <div><div class="text-gray-500">Harga Diskon</div><div class="font-medium text-gray-900">${product.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(product.discount_price) : '-'}</div></div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                            <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-600 hover:text-blue-800 flex items-center text-sm"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit</a>
                            <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-600 hover:text-green-800 flex items-center text-sm"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Print QR</a>
                            <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 flex items-center text-sm"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus</button>
                            </form>
                        </div>
                    </div>`;
            });

            if (currentGroup !== '') {
                mobileHtml += '</div>';
            }

            desktopTableBody.innerHTML = desktopHtml;
            mobileCards.innerHTML = mobileHtml;

            updatePagination(data.pagination);
        })
        .catch(err => {
            console.error(err);
            showError('Terjadi kesalahan saat memuat data.');
        });
    }

    function showError(message) {
        const desktopTableBody = document.getElementById('desktop-table-body');
        const mobileCards = document.getElementById('mobile-cards');
        desktopTableBody.innerHTML = `<div class="bg-white p-6 text-center text-red-500">${message}</div>`;
        mobileCards.innerHTML = `<div class="text-center text-red-500 p-4">${message}</div>`;
        document.getElementById('desktop-pagination').innerHTML = '';
        document.getElementById('mobile-pagination').innerHTML = '';
    }

    function updatePagination(pagination) {
        const desktopPagination = document.getElementById('desktop-pagination');
        const mobilePagination = document.getElementById('mobile-pagination');

        if (!pagination || pagination.last_page <= 1) {
            desktopPagination.innerHTML = '';
            mobilePagination.innerHTML = '';
            return;
        }

        let html = '<nav class="pagination flex space-x-2">';
        html += `<a href="#" class="pagination-link ${pagination.current_page === 1 ? 'disabled' : ''}" data-page="${pagination.current_page - 1}">Previous</a>`;

        const delta = 2;
        const start = Math.max(1, pagination.current_page - delta);
        const end = Math.min(pagination.last_page, pagination.current_page + delta);

        for (let i = start; i <= end; i++) {
            html += `<a href="#" class="pagination-link ${i === pagination.current_page ? 'active' : ''}" data-page="${i}">${i}</a>`;
        }

        html += `<a href="#" class="pagination-link ${pagination.current_page === pagination.last_page ? 'disabled' : ''}" data-page="${pagination.current_page + 1}">Next</a>`;
        html += '</nav>';

        desktopPagination.innerHTML = html;
        mobilePagination.innerHTML = html;

        document.querySelectorAll('.pagination-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                if (!this.classList.contains('disabled')) {
                    const page = this.getAttribute('data-page');
                    performSearch(
                        document.getElementById('search-input').value.trim(),
                        document.getElementById('size-input').value.trim(),
                        document.getElementById('low-stock-filter').checked,
                        page
                    );
                }
            });
        });
    }

    // Auto dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection