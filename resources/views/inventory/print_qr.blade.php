@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6 md:py-12" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-500 rounded-full mb-4">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Cetak QR Code</h1>
            <p class="text-gray-400">Cetak QR Code untuk produk {{ $product->name ?? 'tidak diketahui' }} - Toko Sepatu By Sovan</p>
        </div>

        <!-- QR Code Print Area -->
        <div class="bg-gray-800 rounded-2xl shadow-2xl p-6 md:p-8 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg md:text-xl font-semibold text-white">QR Code untuk {{ $product->name ?? '-' }}</h2>
                <button onclick="window.print()" class="bg-orange-500 text-black px-6 py-3 rounded-xl hover:bg-orange-400 transition-all duration-200 flex items-center font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Sekarang
                </button>
            </div>

            <!-- QR Code Grid for Printing -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6 print-area">
                @for ($i = 0; $i < ($product->stock ?? 0); $i++)
                    <div class="bg-white rounded-xl p-4 text-center print-item border border-gray-200 shadow-sm hover:shadow-md transition-all">
                        <h3 class="text-base font-bold text-gray-900 mb-3">Toko Sepatu By Sovan</h3>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-24 w-24 mx-auto mb-3" onerror="this.src='{{ asset('images/qr-placeholder.png') }}'; this.nextElementSibling.style.display='block';">
                        <div class="text-xs text-red-500 text-center" style="display: none;">QR Code gagal dimuat</div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $product->name ?? '-' }}</h3>
                        <p class="text-xs text-gray-600">Ukuran: {{ $product->size ?? '-' }}</p>
                        <p class="text-xs text-gray-600">Warna: {{ $product->color ?? '-' }}</p>
                        <p class="text-xs text-gray-600">Harga: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Scan untuk detail produk</p>
                    </div>
                @endfor
                @if (($product->stock ?? 0) == 0)
                    <div class="col-span-full text-center bg-gray-700 rounded-xl p-6 text-gray-300">
                        Tidak ada stok untuk produk ini. Silakan tambah stok terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('inventory.index') . (request()->query('search') ? '?search=' . request()->query('search') : '') }}" class="flex items-center justify-center px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Inventory
            </a>
        </div>
    </div>
</div>

<style>
    /* Animasi untuk elemen print area */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bg-gray-800 {
        animation: slideUp 0.5s ease-out;
    }

    .print-item:hover {
        transform: translateY(-2px);
        transition: all 0.2s;
    }

    /* Styling untuk cetak */
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: #fff !important;
        }
        .print-item {
            border: 1px solid #000 !important;
            page-break-inside: avoid;
            break-inside: avoid;
            background: #fff !important;
            box-shadow: none !important;
        }
        .text-gray-600, .text-gray-500, .text-gray-900 {
            color: #000 !important;
        }
        img {
            filter: none !important;
            width: 100px !important;
            height: 100px !important;
        }
        .shadow, .bg-gray-800, .bg-white {
            box-shadow: none !important;
            background: none !important;
        }
        @page {
            margin: 1cm;
        }
        .text-red-500 {
            display: none !important;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
    }

    /* Responsivitas untuk grid */
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<script>
    // Animasi untuk tombol cetak
    document.addEventListener('DOMContentLoaded', function() {
        const printButton = document.querySelector('button[onclick="window.print()"]');
        printButton.addEventListener('click', () => {
            printButton.classList.add('animate-pulse');
            setTimeout(() => printButton.classList.remove('animate-pulse'), 500);
        });
    });
</script>
@endsection