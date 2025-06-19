@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 sm:px-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Cetak QR Code</h1>
            <p class="text-base text-gray-600 mt-1">Produk: {{ $product->name ?? 'Tidak Diketahui' }} - Toko Sepatu By Sovan</p>
        </div>

        <!-- QR Code Print Area -->
        <div class="print-area grid gap-4">
            @forelse ($product->productUnits as $unit)
                <div class="print-item bg-white shadow-sm rounded-lg overflow-hidden" style="width: 50mm; height: 60mm; page-break-after: always; display: flex; flex-direction: column; align-items: center; justify-content: space-between; border: 1px solid #e5e7eb; padding: 2mm; box-sizing: border-box;">
                    <div class="text-center w-full">
                        <p class="text-xs font-bold text-gray-900 m-0 uppercase tracking-wide" style="line-height: 1.2; max-width: 100%; text-align: center;">Toko Sepatu By Sovan</p>
                    </div>
                    <div class="flex justify-center w-full" style="margin: 2mm 0;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($unit->qr_code ?? 'https://example.com') }}" alt="QR Code for Unit {{ $unit->unit_code }}" style="width: 24mm; height: 24mm;" onerror="this.nextElementSibling.style.display='block';">
                        <div class="text-xs text-red-500 text-center font-medium" style="display: none;">Gagal Memuat QR</div>
                    </div>
                    <div class="text-center w-full">
                        <p class="text-xs font-semibold text-gray-800 m-0" style="line-height: 1.2; max-width: 100%; text-align: center;">{{ Str::limit($product->name ?? '-', 18) }}</p>
                        <p class="text-xs text-gray-600 m-0" style="line-height: 1.2;">Unit: {{ $unit->unit_code ?? '-' }}</p>
                        <p class="text-xs text-gray-600 m-0" style="line-height: 1.2;">Ukuran: {{ $product->size ?? '-' }} | Warna: {{ $product->color ?? '-' }}</p>
                        @if ($product->discount_price)
                            <p class="text-sm font-bold text-red-600 m-0" style="line-height: 1.2;">Harga Diskon: Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-black m-0" style="line-height: 1.2; text-decoration: line-through; text-decoration-line: line-through; text-decoration-style: solid; text-decoration-thickness: 1px;">Harga Asli: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs font-medium text-gray-900 m-0" style="line-height: 1.2;">Harga: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center p-6 bg-white rounded-lg shadow-sm text-gray-700">
                    <p class="text-sm font-medium">Tidak ada unit aktif untuk produk ini. Silakan tambah stok terlebih dahulu.</p>
                </div>
            @endforelse
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4 mt-6">
            <button onclick="window.print()" class="bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center transition duration-200 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak QR Code
            </button>
            <a href="{{ route('inventory.index') . (request()->query('search') ? '?search=' . request()->query('search') : '') }}" class="flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg text-sm font-medium transition duration-200 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<style>
    /* Base styles for screen */
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.5;
    }

    .print-item {
        background: #ffffff;
        color: #000000;
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    .print-item:hover {
        transform: scale(1.02);
    }

    /* Print-specific styles */
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
            width: 50mm;
            background: #ffffff !important;
        }
        .print-item {
            width: 50mm !important;
            height: 60mm !important;
            border: 1px solid #000000 !important;
            padding: 2mm !important;
            margin: 0 !important;
            page-break-after: always;
            break-inside: avoid;
            box-shadow: none !important;
            background: #ffffff !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
        }
        img {
            width: 24mm !important;
            height: 24mm !important;
            filter: contrast(100%) brightness(100%) !important;
        }
        .text-red-500 {
            display: none !important;
        }
        p {
            margin: 0 !important;
            line-height: 1.2 !important;
            font-size: 0.75rem !important;
        }
        .text-sm.font-bold.text-red-600 {
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            color: #dc2626 !important;
        }
        .text-xs.font-bold.text-black {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            color: #000000 !important;
            text-decoration: line-through !important;
            text-decoration-line: line-through !important;
            text-decoration-style: solid !important;
            text-decoration-thickness: 1px !important;
        }
        @page {
            size: 50mm 60mm;
            margin: 0;
        }
        button, a, h1, p:not(.print-item p) {
            display: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printButton = document.querySelector('button[onclick="window.print()"]');
        printButton.addEventListener('click', () => {
            printButton.classList.add('animate-pulse');
            setTimeout(() => printButton.classList.remove('animate-pulse'), 500);
        });
    });
</script>
@endsection