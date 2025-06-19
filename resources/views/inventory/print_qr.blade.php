<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - Toko Sepatu By Sovan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
            overflow-x: hidden;
        }

        .print-item {
            background: #ffffff;
            color: #000000;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid #7c3aed;
            border-radius: 12px;
            overflow: hidden;
        }

        .print-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .header-title {
            background: linear-gradient(to right, #7c3aed, #db2777);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: bounceIn 1s ease;
        }

        .btn-print, .btn-back {
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            transform: scale(1.1);
            background: #6d28d9;
        }

        .btn-back:hover {
            transform: scale(1.1);
            background: #4b5563;
        }

        .qr-code-img {
            transition: transform 0.5s ease;
        }

        .qr-code-img:hover {
            transform: rotate(360deg);
        }

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
</head>
<body>
    <div class="min-h-screen py-12 bg-gradient-to-br from-purple-100 to-pink-100">
        <div class="max-w-7xl mx-auto px-6 sm:px-8">
            <!-- Header -->
            <div class="text-center mb-8 animate__animated animate__fadeInDown">
                <h1 class="text-4xl font-extrabold header-title">Cetak QR Code</h1>
                <p class="text-lg text-gray-700 mt-2 animate__animated animate__fadeIn">Produk: {{ $product->name ?? 'Tidak Diketahui' }} - Toko Sepatu By Sovan</p>
            </div>

            <!-- QR Code Print Area -->
            <div class="print-area grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($product->productUnits as $unit)
                    <div class="print-item bg-white shadow-lg rounded-lg animate__animated animate__zoomIn" style="width: 50mm; height: 60mm; page-break-after: always; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 2mm; box-sizing: border-box;">
                        <div class="text-center w-full">
                            <p class="text-xs font-bold text-purple-900 m-0 uppercase tracking-wide" style="line-height: 1.2;">Toko Sepatu By Sovan</p>
                        </div>
                        <div class="flex justify-center w-full" style="margin: 2mm 0;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($unit->qr_code ?? 'https://example.com') }}" alt="QR Code for Unit {{ $unit->unit_code }}" class="qr-code-img" style="width: 24mm; height: 24mm;" onerror="this.nextElementSibling.style.display='block';">
                            <div class="text-xs text-red-500 text-center font-medium" style="display: none;">Gagal Memuat QR</div>
                        </div>
                        <div class="text-center w-full">
                            <p class="text-xs font-semibold text-gray-800 m-0" style="line-height: 1.2;">{{ Str::limit($product->name ?? '-', 18) }}</p>
                            <p class="text-xs text-gray-600 m-0" style="line-height: 1.2;">Unit: {{ $unit->unit_code ?? '-' }}</p>
                            <p class="text-xs text-gray-600 m-0" style="line-height: 1.2;">Ukuran: {{ $product->size ?? '-' }} | Warna: {{ $product->color ?? '-' }}</p>
                            @if ($product->discount_price)
                                <p class="text-sm font-bold text-red-600 m-0" style="line-height: 1.2;">Harga Diskon: Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                                <p class="text-xs font-bold text-black m-0" style="line-height: 1.2; text-decoration: line-through;">Harga Asli: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                            @else
                                <p class="text-xs font-medium text-gray-900 m-0" style="line-height: 1.2;">Harga: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center p-6 bg-white rounded-lg shadow-lg animate__animated animate__shakeX">
                        <p class="text-sm font-medium text-gray-700">Tidak ada unit aktif untuk produk ini. Silakan tambah stok terlebih dahulu.</p>
                    </div>
                @endforelse
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-6 mt-8">
                <button onclick="window.print()" class="btn-print bg-purple-700 text-white px-6 py-3 rounded-lg text-sm font-medium flex items-center transition duration-300 hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 animate__animated animate__pulse animate__infinite">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak QR Code
                </button>
                <a href="{{ route('inventory.index') . (request()->query('search') ? '?search=' . request()->query('search') : '') }}" class="btn-back flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg text-sm font-medium transition duration-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 animate__animated animate__pulse animate__infinite">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.querySelector('.btn-print');
            printButton.addEventListener('click', () => {
                printButton.classList.add('animate__animated', 'animate__tada');
                setTimeout(() => printButton.classList.remove('animate__animated', 'animate__tada'), 1000);
            });

            const backButton = document.querySelector('.btn-back');
            backButton.addEventListener('click', () => {
                backButton.classList.add('animate__animated', 'animate__swing');
                setTimeout(() => backButton.classList.remove('animate__animated', 'animate__swing'), 1000);
            });
        });
    </script>
</body>
</html>