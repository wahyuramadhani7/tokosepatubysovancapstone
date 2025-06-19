<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - Toko Sepatu By Sovan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #6b7280);
            color: #1f2937;
        }

        .container {
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1rem;
            color: #e5e7eb;
        }

        .print-area {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(55mm, 1fr));
            gap: 1rem;
            width: 100%;
            max-width: 80rem;
        }

        .print-item {
            background: #ffffff;
            border-radius: 0.75rem;
            width: 50mm;
            height: 60mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 2mm;
            box-sizing: border-box;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: popUp 0.5s ease forwards;
        }

        @keyframes popUp {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .print-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .print-item p {
            margin: 0;
            line-height: 1.2;
            text-align: center;
            font-size: 0.6rem;
            color: #1f2937;
        }

        .print-item img {
            width: 20mm;
            height: 20mm;
            margin: 2mm 0;
            transition: transform 0.3s ease;
            border-radius: 0.25rem;
        }

        .print-item img:hover {
            transform: scale(1.05);
        }

        .error-text {
            display: none;
            color: #ef4444;
            font-size: 0.6rem;
            font-weight: 600;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn {
            display: flex;
            align-items: center;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(to right, #10b981, #059669);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: linear-gradient(to right, #f87171, #ef4444);
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: linear-gradient(to right, #ef4444, #dc2626);
            transform: translateY(-2px);
        }

        .btn svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }

        .no-data {
            background: #ffffff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            animation: popUp 0.8s ease;
        }

        @media print {
            body {
                background: #ffffff !important;
            }
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
            }
            img {
                width: 20mm !important;
                height: 20mm !important;
                filter: contrast(100%) brightness(100%) !important;
            }
            .error-text {
                display: none !important;
            }
            p {
                margin: 0 !important;
                line-height: 1.2 !important;
                font-size: 0.6rem !important;
            }
            .line-through {
                text-decoration: line-through !important;
            }
            .text-sm.font-bold.text-red-600 {
                font-size: 0.7rem !important;
                font-weight: 700 !important;
                color: #ef4444 !important;
            }
            .text-xs.font-bold.text-black {
                font-size: 0.6rem !important;
                font-weight: 700 !important;
                color: #000000 !important;
            }
            @page {
                size: 50mm 60mm;
                margin: 0;
            }
            .container > *:not(.print-area),
            button,
            a,
            h1,
            p:not(.print-item p) {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cetak QR Code</h1>
            <p>Produk: {{ $product->name ?? 'Tidak Diketahui' }} - Toko Sepatu By Sovan</p>
        </div>

        <div class="print-area">
            @forelse ($product->productUnits as $unit)
                <div class="print-item">
                    <p class="font-bold uppercase tracking-wide text-gray-900">Toko Sepatu By Sovan</p>
                    <div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($unit->qr_code ?? 'https://example.com') }}" alt="QR Code for Unit {{ $unit->unit_code }}" onerror="this.nextElementSibling.style.display='block';">
                        <div class="error-text">Gagal Memuat QR</div>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ Str::limit($product->name ?? '-', 16) }}</p>
                        <p class="text-gray-600">Unit: {{ $unit->unit_code ?? '-' }}</p>
                        <p class="text-gray-600">Ukuran: {{ $product->size ?? '-' }} | Warna: {{ $product->color ?? '-' }}</p>
                        @if ($product->discount_price)
                            <p class="text-sm font-bold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-black line-through">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        @else
                            <p class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <p>Tidak ada unit aktif untuk produk ini. Silakan tambah stok terlebih dahulu.</p>
                </div>
            @endforelse
        </div>

        <div class="buttons">
            <button onclick="window.print()" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak QR Code
            </button>
            <a href="{{ route('inventory.index') . (request()->query('search') ? '?search=' . request()->query('search') : '') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.querySelector('.btn-primary');
            printButton.addEventListener('click', () => {
                printButton.classList.add('animate-pulse');
                setTimeout(() => printButton.classList.remove('animate-pulse'), 500);
            });

            const printItems = document.querySelectorAll('.print-item');
            printItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>