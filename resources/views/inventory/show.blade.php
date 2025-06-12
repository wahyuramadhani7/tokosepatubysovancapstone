@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6 md:py-12" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-500 rounded-full mb-4">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Detail Produk</h1>
            <p class="text-gray-400">Informasi lengkap produk dari QR scan</p>
        </div>

        <!-- Main Product Card -->
        <div class="bg-gray-800 rounded-2xl shadow-2xl overflow-hidden mb-6">
            <!-- Product Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-center">
                <h2 class="text-xl md:text-2xl font-bold text-black mb-2">{{ $product->name ?? 'Nama Produk' }}</h2>
                <div class="flex justify-center items-center space-x-4 text-black/80">
                    <span class="bg-black/20 px-3 py-1 rounded-full text-sm">{{ $product->size ?? 'Size' }}</span>
                    <span class="bg-black/20 px-3 py-1 rounded-full text-sm">{{ $product->color ?? 'Color' }}</span>
                </div>
            </div>

            <!-- Product Content -->
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column - QR Code and Basic Info -->
                    <div class="space-y-6">
                        <!-- QR Code Display -->
                        <div class="bg-white rounded-xl p-6 text-center">
                            @if($product->qr_code ?? false)
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/inventory/' . $product->id . '/verify')) }}" alt="QR Code" class="mx-auto mb-4" style="max-width: 150px; height: auto;">
                            @else
                                <div class="w-32 h-32 mx-auto mb-4 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                            @endif
                            <p class="text-gray-600 text-sm">QR Code Produk</p>
                        </div>

                        <!-- Product ID -->
                        <div class="bg-gray-700 rounded-xl p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Product ID</p>
                                    <p class="text-white font-semibold">#{{ str_pad($product->id ?? '001', 3, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Details -->
                    <div class="space-y-6">
                        <!-- Stock Status -->
                        <div class="bg-gray-700 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-white">Status Stok</h3>
                                @php
                                    $stock = $product->stock ?? 0;
                                    $stockStatus = $stock > 10 ? 'Tersedia' : ($stock > 0 ? 'Stok Terbatas' : 'Habis');
                                    $stockColor = $stock > 10 ? 'text-green-400' : ($stock > 0 ? 'text-yellow-400' : 'text-red-400');
                                    $stockBg = $stock > 10 ? 'bg-green-400/20' : ($stock > 0 ? 'bg-yellow-400/20' : 'bg-red-400/20');
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm {{ $stockBg }} {{ $stockColor }}">{{ $stockStatus }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-400">Jumlah Stok</span>
                                        <span class="text-white font-semibold">{{ $stock }} unit</span>
                                    </div>
                                    <div class="w-full bg-gray-600 rounded-full h-2">
                                        @php
                                            $maxStock = 50; // Assume max stock for progress bar
                                            $percentage = min(($stock / $maxStock) * 100, 100);
                                            $barColor = $stock > 10 ? 'bg-green-400' : ($stock > 0 ? 'bg-yellow-400' : 'bg-red-400');
                                        @endphp
                                        <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Information -->
                        <div class="bg-gradient-to-r from-orange-500/20 to-orange-600/20 rounded-xl p-6 border border-orange-500/30">
                            <h3 class="text-lg font-semibold text-white mb-4">Informasi Harga</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Harga Jual</span>
                                    <span class="text-2xl font-bold text-orange-400">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</span>
                                </div>
                                @if(isset($product->purchase_price))
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Harga Beli</span>
                                    <span class="text-gray-300">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Info -->
                        @if(isset($product->description) || isset($product->category))
                        <div class="bg-gray-700 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Informasi Tambahan</h3>
                            <div class="space-y-3">
                                @if(isset($product->category))
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                                    </svg>
                                    <span class="text-gray-300">Kategori: {{ $product->category }}</span>
                                </div>
                                @endif
                                @if(isset($product->description))
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-orange-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-gray-300">{{ $product->description }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('inventory.index') }}" class="flex items-center justify-center px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Inventory
            </a>
            <a href="{{ route('inventory.edit', $product->id) }}" class="flex items-center justify-center px-6 py-3 bg-orange-500 text-black rounded-xl hover:bg-orange-400 transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Produk
            </a>
            <a href="{{ route('inventory.print_qr', $product->id) }}" class="flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-500 transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print QR Code
            </a>
        </div>

        <!-- Scan Info -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-full text-gray-400 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Terakhir diakses: {{ now()->format('d M Y, H:i') }} WIB
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bg-gray-800, .bg-gray-700 {
        animation: slideUp 0.5s ease-out;
    }
    
    /* Hover effects */
    .bg-gray-700:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    /* Mobile responsive adjustments */
    @media (max-width: 768px) {
        .grid-cols-1.md\\:grid-cols-2 {
            gap: 1.5rem;
        }
    }
</style>

<script>
    // Add some interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Add pulse animation to stock status if low
        const stockElement = document.querySelector('.text-red-400, .text-yellow-400');
        if (stockElement && stockElement.classList.contains('text-red-400')) {
            stockElement.parentElement.style.animation = 'pulse 2s infinite';
        }
    });
    
    // Add CSS for pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection