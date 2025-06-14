@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">
            PRINT QR CODE
        </h1>

        <!-- QR Code Print Area -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg md:text-xl font-semibold">QR Code untuk {{ $product->name ?? '-' }}</h2>
                <button onclick="window.print()" class="bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
            </div>

            <!-- QR Code Grid for Printing -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 print-area">
                @for ($i = 0; $i < ($product->stock ?? 0); $i++)
                    <div class="border border-gray-200 rounded-lg p-4 text-center print-item">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('inventory.update-physical-stock-direct', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-24 w-24 mx-auto mb-2" onerror="this.src='{{ asset('images/qr-placeholder.png') }}'; this.nextElementSibling.style.display='block';">
                        <div class="text-xs text-red-500 text-center" style="display: none;">QR Code gagal dimuat</div>
                        <h3 class="text-sm font-semibold">{{ $product->name ?? '-' }}</h3>
                        <p class="text-xs text-gray-600">Ukuran: {{ $product->size ?? '-' }}</p>
                        <p class="text-xs text-gray-600">Warna: {{ $product->color ?? '-' }}</p>
                        <p class="text-xs text-gray-600">Harga: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Scan untuk detail produk</p>
                    </div>
                @endfor
                @if (($product->stock ?? 0) == 0)
                    <div class="text-center col-span-full text-gray-500">
                        Tidak ada stok untuk produk ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide all elements except print area */
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
        }
        /* Ensure text and image are clear */
        .text-gray-600, .text-gray-500 {
            color: #000 !important;
        }
        img {
            filter: none !important;
            width: 100px !important;
            height: 100px !important;
        }
        /* Remove shadows and unnecessary styles */
        .shadow, .bg-white {
            box-shadow: none !important;
            background: none !important;
        }
        /* Force print background graphics */
        @page {
            margin: 1cm;
        }
        /* Hide error message in print */
        .text-red-500 {
            display: none !important;
        }
        /* Ensure grid layout for printing */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
    }
</style>
@endsection