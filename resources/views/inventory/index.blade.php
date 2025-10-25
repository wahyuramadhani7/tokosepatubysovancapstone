@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <div class="rounded-lg p-4 md:p-6 mb-6" style="background-color: #292929;">
            <h2 class="text-lg md:text-xl font-semibold mb-4 md:mb-6 text-white text-center">INVENTORY INFORMATION</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Stok Menipis -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Stok Menipis</h3>
                        <p id="low-stock-count" class="text-gray-600 text-base md:text-lg font-medium">{{ $lowStockProducts ?? 0 }}</p>
                    </div>
                </div>

                <!-- Total Unit -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Total Unit</h3>
                        <p id="total-stock" class="text-gray-600 text-base md:text-lg font-medium">{{ $totalStock ?? 0 }}</p>
                    </div>
                </div>

                <!-- Dropdown: Jumlah Unit per Produk (Brand + Model) -->
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m-5 10V7m5 10h2a2 2 0 002-2V7a2 2 0 00-2-2h-2M7 7H5a2 2 0 00-2 2v6a2 2 0 002 2h2" />
                    </svg>
                    <div class="w-full">
                        <h3 class="text-sm md:text-base font-semibold uppercase">Jumlah Unit per Produk</h3>
                        <select id="product-counts" class="w-full bg-white text-gray-600 text-sm md:text-base rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-orange-500 uppercase-text">
                            @forelse ($brandModelCounts ?? [] as $label => $count)
                                <option value="{{ $label }}">{{ $label }} ({{ $count }} unit)</option>
                            @empty
                                <option value="">Tidak ada produk</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar and Filters -->
        <div style="background-color: #292929;" class="p-4 md:p-6 mb-6 rounded-lg shadow-sm">
            <form id="search-form" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full">
                <div class="relative flex-grow">
                    <input type="text" name="search" id="search-input" class="w-full bg-white text-black text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500" placeholder="Cari produk..." value="{{ $searchTerm ?? '' }}">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="relative flex-grow">
                    <input type="text" name="size" id="size-input" class="w-full bg-white text-black text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500" placeholder="Cari ukuran..." value="{{ $sizeTerm ?? '' }}">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="low-stock-filter" name="low_stock" class="mr-2 h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded" {{ isset($lowStockFilter) && $lowStockFilter ? 'checked' : '' }}>
                    <label for="low-stock-filter" class="text-sm md:text-base text-white">Stok Menipis</label>
                </div>
                <button type="submit" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors w-full md:w-auto">Cari</button>
            </form>
        </div>

        <!-- Action Buttons -->
        <div style="background-color: #292929;" class="p-4 md:p-6 mb-6 rounded-lg shadow-sm">
            <div class="flex flex-col md:flex-row gap-3">
                <a href="{{ route('inventory.create') }}" class="bg-white text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-gray-100 transition-colors flex items-center w-full md:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </a>
                <a href="{{ route('inventory.stock_opname') }}" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors flex items-center w-full md:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Stock Opname
                </a>
                <a href="{{ route('inventory.history') }}" class="bg-blue-500 text-white font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-blue-600 transition-colors flex items-center w-full md:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Produk
                </a>
                <a href="{{ route('purchase_notes.index') }}" class="bg-purple-500 text-white font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-purple-600 transition-colors flex items-center w-full md:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01M12 12h.01" />
                    </svg>
                    Catatan Barang Masuk
                </a>
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
                <div class="grid grid-cols-10 gap-0">
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">No</div>
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
                    @php $currentGroup = ''; @endphp
                    @forelse ($products ?? collect() as $product)
                        @if($product->product_units_count <= 0) @continue @endif
                        @php
                            $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
                            $model = trim(str_replace($brand, '', $product->name));
                            $label = Str::title("{$brand} {$model}");
                            $rowNumber = ($products->currentPage() - 1) * $products->perPage() + $loop->iteration;
                            $mismatch = $stockMismatches[$product->id] ?? null;
                            $diff = $mismatch['difference'] ?? 0;
                            $physical = $mismatch['physical_stock'] ?? $product->product_units_count;
                            $stockClass = ($diff < 0) ? 'text-red-600' : ($diff > 0 ? 'text-yellow-600' : 'text-black');
                        @endphp
                        @if ($currentGroup !== $label)
                            @if ($currentGroup !== '')
                                <div class="border-t border-gray-300 my-2"></div>
                            @endif
                            <div class="bg-orange-500 text-black font-semibold py-2 px-3 uppercase-text">{{ $label }}</div>
                            @php $currentGroup = $label; @endphp
                        @endif
                        <div class="grid grid-cols-10 gap-0 items-center {{ $loop->iteration % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                            <div class="p-3 text-black text-center">{{ $rowNumber }}</div>
                            <div class="p-3 text-black">
                                <div class="flex items-center">
                                    <span class="uppercase-text">{{ $label }}</span>
                                    @if (in_array($product->id, $newProducts ?? []))
                                        <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>
                                    @elseif (in_array($product->id, $updatedProducts ?? []))
                                        <span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>
                                    @endif
                                </div>
                                @if($mismatch && isset($mismatch['message']))
                                    <p class="text-sm text-red-600 mt-1">{{ $mismatch['message'] }}</p>
                                @endif
                            </div>
                            <div class="p-3 text-black text-center">{{ $product->size ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $product->color ?? '-' }}</div>
                            <div class="p-3 font-medium text-center {{ $product->product_units_count < 5 ? 'text-red-600' : 'text-black' }}">{{ $product->product_units_count }}</div>
                            <div class="p-3 font-medium text-center {{ $stockClass }}">{{ $physical }}</div>
                            <div class="p-3 text-black text-right">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                            <div class="p-3 text-black text-right">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div>
                            <div class="p-3 text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                            </div>
                            <div class="p-3">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-600 hover:text-green-800" title="Cetak QR">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
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
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4" id="mobile-cards">
            @php $currentGroup = ''; @endphp
            @forelse ($products ?? collect() as $product)
                @if($product->product_units_count <= 0) @continue @endif
                @php
                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
                    $model = trim(str_replace($brand, '', $product->name));
                    $label = Str::title("{$brand} {$model}");
                    $rowNumber = ($products->currentPage() - 1) * $products->perPage() + $loop->iteration;
                    $mismatch = $stockMismatches[$product->id] ?? null;
                    $diff = $mismatch['difference'] ?? 0;
                    $physical = $mismatch['physical_stock'] ?? $product->product_units_count;
                    $stockClass = ($diff < 0) ? 'text-red-600' : ($diff > 0 ? 'text-yellow-600' : 'text-gray-900');
                @endphp
                @if ($currentGroup !== $label)
                    @if ($currentGroup !== '') </div> @endif
                    <div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg uppercase-text">{{ $label }}</div>
                    <div class="space-y-4">
                    @php $currentGroup = $label; @endphp
                @endif
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="mb-2 flex items-center">
                        <div>
                            <h3 class="font-medium text-gray-900 flex items-center">
                                No: {{ $rowNumber }} - <span class="uppercase-text">{{ $label }}</span>
                                @if (in_array($product->id, $newProducts ?? []))
                                    <span class="ml-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">Baru</span>
                                @elseif (in_array($product->id, $updatedProducts ?? []))
                                    <span class="ml-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">Diperbarui</span>
                                @endif
                            </h3>
                            <div class="text-sm text-gray-500">{{ $product->size ?? '-' }} | <span class="uppercase-text">{{ $product->color ?? '-' }}</span></div>
                            @if($mismatch && isset($mismatch['message']))
                                <p class="text-sm text-red-600 mt-1">{{ $mismatch['message'] }}</p>
                            @endif
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div><div class="text-xs text-gray-500">Stok Sistem</div><div class="font-medium {{ $product->product_units_count < 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->product_units_count }}</div></div>
                        <div><div class="text-xs text-gray-500">Stok Fisik</div><div class="font-medium {{ $stockClass }}">{{ $physical }}</div></div>
                        <div><div class="text-xs text-gray-500">Harga Jual</div><div class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div></div>
                        <div><div class="text-xs text-gray-500">Harga Diskon</div><div class="font-medium text-gray-900">{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</div></div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 flex items-center"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> Edit</a>
                        <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-500 hover:text-green-800 flex items-center"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> Print QR</a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 flex items-center"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-4">Tidak ada produk ditemukan dengan stok lebih dari 0.</div>
            @endforelse
            @if ($currentGroup !== '') </div> @endif
        </div>

        <!-- Pagination Mobile -->
        <div class="mt-4 flex justify-center" id="mobile-pagination">
            {{ $products->links() }}
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
    .grid-cols-10 { grid-template-columns: repeat(10, minmax(0, 1fr)); }
    .uppercase-text { text-transform: uppercase; }
    .pagination { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; justify-content: center; }
    .pagination a { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 4px; color: #2d3748; text-decoration: none; }
    .pagination a:hover { background-color: #edf2f7; }
    .pagination .active { background-color: #f6ad55; color: #fff; border-color: #f6ad55; }
    .pagination .disabled { color: #a0aec0; pointer-events: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const sizeInput = document.getElementById('size-input');
    const lowStockFilter = document.getElementById('low-stock-filter');
    const form = document.getElementById('search-form');
    let debounceTimer;

    [searchInput, sizeInput, lowStockFilter].forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => form.dispatchEvent(new Event('submit')), 500);
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(1);
    });

    function performSearch(page) {
        const search = searchInput.value.trim();
        const size = sizeInput.value.trim();
        const lowStock = lowStockFilter.checked;

        if (search.length < 2 && size.length < 1 && !lowStock) {
            showError('Masukkan minimal 2 karakter, ukuran, atau centang stok menipis.');
            return;
        }

        const url = new URL('{{ route("inventory.search") }}');
        if (search) url.searchParams.set('search', search);
        if (size) url.searchParams.set('size', size);
        if (lowStock) url.searchParams.set('low_stock', 1);
        url.searchParams.set('page', page);

        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            updateUI(data);
            updatePagination(data.pagination, search, size, lowStock);
        })
        .catch(() => showError('Gagal memuat data.'));
    }

    function updateUI(data) {
        document.getElementById('low-stock-count').textContent = data.lowStockProducts;
        document.getElementById('total-stock').textContent = data.totalStock;

        const select = document.getElementById('product-counts');
        select.innerHTML = '';
        if (data.brandModelCounts && Object.keys(data.brandModelCounts).length) {
            Object.entries(data.brandModelCounts).forEach(([label, count]) => {
                select.add(new Option(`${label} (${count} unit)`, label));
            });
        } else {
            select.add(new Option('Tidak ada produk', ''));
        }

        renderTable(data.products, data);
        renderMobile(data.products, data);
    }

    function renderTable(products, data) {
        const tbody = document.getElementById('desktop-table-body');
        tbody.innerHTML = '';
        if (!products.length) {
            tbody.innerHTML = '<div class="bg-white p-6 text-center text-gray-500">Tidak ada produk ditemukan.</div>';
            return;
        }

        let currentGroup = '';
        products.forEach((p, i) => {
            if (p.stock <= 0) return;
            const label = p.brand + ' ' + p.model;
            const rowNum = (data.pagination.current_page - 1) * data.pagination.per_page + i + 1;
            const mismatch = data.stockMismatches?.[p.id] || {};
            const physical = mismatch.physical_stock ?? p.stock;
            const diff = mismatch.difference ?? 0;
            const stockClass = diff < 0 ? 'text-red-600' : diff > 0 ? 'text-yellow-600' : 'text-black';

            if (currentGroup !== label) {
                if (currentGroup) tbody.insertAdjacentHTML('beforeend', '<div class="border-t border-gray-300 my-2"></div>');
                tbody.insertAdjacentHTML('beforeend', `<div class="bg-orange-500 text-black font-semibold py-2 px-3 uppercase-text">${label}</div>`);
                currentGroup = label;
            }

            const row = `
                <div class="grid grid-cols-10 gap-0 items-center ${i % 2 === 0 ? 'bg-white' : 'bg-gray-200'}">
                    <div class="p-3 text-center">${rowNum}</div>
                    <div class="p-3"><div class="flex items-center"><span class="uppercase-text">${label}</span>${data.newProducts.includes(p.id) ? '<span class="ml-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Baru</span>' : data.updatedProducts.includes(p.id) ? '<span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Diperbarui</span>' : ''}</div>${mismatch.message ? `<p class="text-sm text-red-600 mt-1">${mismatch.message}</p>` : ''}</div>
                    <div class="p-3 text-center">${p.size || '-'}</div>
                    <div class="p-3 text-center uppercase-text">${p.color || '-'}</div>
                    <div class="p-3 text-center font-medium ${p.stock < 5 ? 'text-red-600' : ''}">${p.stock}</div>
                    <div class="p-3 text-center font-medium ${stockClass}">${physical}</div>
                    <div class="p-3 text-right">Rp ${new Intl.NumberFormat('id-ID').format(p.selling_price)}</div>
                    <div class="p-3 text-right">${p.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(p.discount_price) : '-'}</div>
                    <div class="p-3 text-center"><img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route("inventory.show", ":id") }}'.replace(':id', p.id))}" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset("images/qr-placeholder.png") }}';"></div>
                    <div class="p-3"><div class="flex justify-center space-x-3">
                        <a href="{{ route('inventory.edit', ':id') }}".replace(':id', p.id) class="text-blue-600 hover:text-blue-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                        <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', p.id) class="text-green-600 hover:text-green-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg></a>
                        <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', p.id) method="POST" class="inline" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></form>
                    </div></div>
                </div>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderMobile(products, data) {
        const container = document.getElementById('mobile-cards');
        container.innerHTML = '';
        if (!products.length) {
            container.innerHTML = '<div class="text-center text-gray-500 p-4">Tidak ada produk.</div>';
            return;
        }

        let currentGroup = '';
        products.forEach((p, i) => {
            if (p.stock <= 0) return;
            const label = p.brand + ' ' + p.model;
            const rowNum = (data.pagination.current_page - 1) * data.pagination.per_page + i + 1;
            const mismatch = data.stockMismatches?.[p.id] || {};
            const physical = mismatch.physical_stock ?? p.stock;
            const diff = mismatch.difference ?? 0;
            const stockClass = diff < 0 ? 'text-red-600' : diff > 0 ? 'text-yellow-600' : 'text-gray-900';

            if (currentGroup !== label) {
                if (currentGroup) container.insertAdjacentHTML('beforeend', '</div>');
                container.insertAdjacentHTML('beforeend', `<div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg uppercase-text">${label}</div><div class="space-y-4">`);
                currentGroup = label;
            }

            const card = `
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="mb-2 flex items-center">
                        <div>
                            <h3 class="font-medium text-gray-900 flex items-center">No: ${rowNum} - <span class="uppercase-text">${label}</span>${data.newProducts.includes(p.id) ? '<span class="ml-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Baru</span>' : data.updatedProducts.includes(p.id) ? '<span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Diperbarui</span>' : ''}</h3>
                            <div class="text-sm text-gray-500">${p.size || '-'} | <span class="uppercase-text">${p.color || '-'}</span></div>
                            ${mismatch.message ? `<p class="text-sm text-red-600 mt-1">${mismatch.message}</p>` : ''}
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route("inventory.show", ":id") }}'.replace(':id', p.id))}" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset("images/qr-placeholder.png") }}';">
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div><div class="text-xs text-gray-500">Stok Sistem</div><div class="font-medium ${p.stock < 5 ? 'text-red-600' : 'text-gray-900'}">${p.stock}</div></div>
                        <div><div class="text-xs text-gray-500">Stok Fisik</div><div class="font-medium ${stockClass}">${physical}</div></div>
                        <div><div class="text-xs text-gray-500">Harga Jual</div><div class="font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(p.selling_price)}</div></div>
                        <div><div class="text-xs text-gray-500">Harga Diskon</div><div class="font-medium text-gray-900">${p.discount_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(p.discount_price) : '-'}</div></div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', ':id') }}".replace(':id', p.id) class="text-blue-500 hover:text-blue-700 flex items-center"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg> Edit</a>
                        <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', p.id) class="text-green-500 hover:text-green-800 flex items-center"><svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg> Print QR</a>
                        <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', p.id) method="POST" class="inline" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-700 flex items-center"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Delete</button></form>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', card);
        });
        if (currentGroup) container.insertAdjacentHTML('beforeend', '</div>');
    }

    function updatePagination(pagination, search, size, lowStock) {
        const build = (id) => {
            const el = document.getElementById(id);
            if (!pagination || pagination.last_page <= 1) { el.innerHTML = ''; return; }
            let html = '<nav class="pagination flex space-x-2">';
            html += `<a href="#" class="pagination-link ${pagination.current_page === 1 ? 'disabled' : ''}" data-page="${pagination.current_page - 1}">&laquo; Prev</a>`;
            for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.last_page, pagination.current_page + 2); i++) {
                html += `<a href="#" class="pagination-link ${i === pagination.current_page ? 'active' : ''}" data-page="${i}">${i}</a>`;
            }
            html += `<a href="#" class="pagination-link ${pagination.current_page === pagination.last_page ? 'disabled' : ''}" data-page="${pagination.current_page + 1}">Next &raquo;</a></nav>`;
            el.innerHTML = html;
            el.querySelectorAll('.pagination-link').forEach(l => l.addEventListener('click', e => {
                e.preventDefault();
                if (!l.classList.contains('disabled')) performSearch(l.dataset.page);
            }));
        };
        build('desktop-pagination');
        build('mobile-pagination');
    }

    function showError(msg) {
        ['desktop-table-body', 'mobile-cards'].forEach(id => {
            document.getElementById(id).innerHTML = `<div class="bg-white p-6 text-center text-red-500">${msg}</div>`;
        });
        document.getElementById('product-counts').innerHTML = '<option>Tidak ada produk</option>';
        ['desktop-pagination', 'mobile-pagination'].forEach(id => document.getElementById(id).innerHTML = '');
    }

    setTimeout(() => document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(a => a.remove()), 5000);
});
</script>
@endsection