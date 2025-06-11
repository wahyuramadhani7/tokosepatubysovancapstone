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

            <!-- QR Code and Product Info for Printing -->
            <div class="border border-gray-200 rounded-lg p-6 max-w-sm mx-auto print-area">
                <div class="text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=128x128&data={{ urlencode(url('/inventory/' . $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-32 w-32 mx-auto mb-4" onerror="this.src='{{ asset('images/qr-placeholder.png') }}'; this.nextElementSibling.style.display='block';">
                    <div class="text-xs text-red-500 text-center" style="display: none;">QR Code gagal dimuat</div>
                    <h3 class="text-lg font-semibold">{{ $product->name ?? '-' }}</h3>
                    <p class="text-sm text-gray-600">Ukuran: {{ $product->size ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Warna: {{ $product->color ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Harga: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-2">Scan untuk detail produk</p>
                </div>
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
            border: 1px solid #000 !important;
            background: #fff !important;
        }
        /* Ensure text and image are clear */
        .text-gray-600, .text-gray-500 {
            color: #000 !important;
        }
        img {
            filter: none !important; /* Prevent grayscale or filter issues */
            width: 128px !important;
            height: 128px !important;
        }
        /* Remove shadows and unnecessary styles */
        .shadow, .bg-white {
            box-shadow: none !important;
            background: none !important;
        }
        /* Force print background graphics */
        @page {
            margin: 0;
        }
        /* Hide error message in print */
        .text-red-500 {
            display: none !important;
        }
    }
</style>
@endsection