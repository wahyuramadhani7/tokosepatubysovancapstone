<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detail produk {{ $product->name ?? 'tidak tersedia' }} di Toko Sepatu by Sovan.">
    <title>Detail Produk - Toko Sepatu by Sovan</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 960px;
            padding: 1.5rem;
            margin: 2rem auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            background: #f97316;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #9ca3af;
            font-size: 1rem;
        }

        .product-card {
            background: #2d2d2d;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        .product-header {
            background: linear-gradient(to right, #f97316, #ea580c);
            padding: 1.5rem;
            text-align: center;
        }

        .product-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .product-header .tags {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .product-header .tag {
            background: rgba(0, 0, 0, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            color: #000;
        }

        .product-content {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .product-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .qr-code {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
        }

        .qr-code img {
            max-width: 9rem;
            height: auto;
            margin-bottom: 1rem;
        }

        .qr-code p {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .product-id {
            background: #3f3f3f;
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            align-items: center;
        }

        .product-id .icon {
            width: 2.5rem;
            height: 2.5rem;
            background: #f97316;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .product-id p {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .product-id p strong {
            color: #fff;
            font-weight: 600;
        }

        .stock-status, .price-info, .unit-list, .product-details {
            background: #3f3f3f;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .stock-status h3, .price-info h2, .unit-list h3, .product-details h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 1rem;
        }

        .stock-status .status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stock-status .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
        }

        .stock-status .stock-details {
            font-size: 0.875rem;
        }

        .stock-status .progress-bar {
            width: 100%;
            background: #4b5563;
            border-radius: 9999px;
            height: 0.5rem;
            margin-top: 0.5rem;
        }

        .stock-status .progress-bar .progress {
            background: #f97316;
            height: 100%;
            border-radius: 9999px;
        }

        .price-info .price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .price-info .price span {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f97316;
        }

        .product-details .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .unit-list .unit-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            background: #4b5563;
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        .unit-list .unit-item img {
            width: 3rem;
            height: 3rem;
            margin-right: 1rem;
        }

        .unit-list .unit-item p {
            color: #d1d5db;
            font-size: 0.875rem;
        }

        .alert-deleted {
            background: #ef4444;
            color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .alert-deleted p {
            margin: 0;
            font-size: 0.875rem;
        }

        .closing-message {
            text-align: center;
            margin-bottom: 2rem;
        }

        .closing-message p:first-child {
            font-size: 1.125rem;
            font-weight: 500;
            color: #d1d5db;
            margin-bottom: 0.5rem;
        }

        .closing-message p:last-child {
            color: #9ca3af;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .product-header h2 {
                font-size: 1.25rem;
            }

            .qr-code img {
                max-width: 7.5rem;
            }

            .product-content {
                grid-template-columns: 1fr;
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                </svg>
            </div>
            <h1>Toko Sepatu by Sovan</h1>
            <p>Detail Produk</p>
        </div>

        <!-- Alert jika produk dihapus -->
        @if (($product->name ?? '') === 'Produk Tidak Ditemukan')
            <div class="alert-deleted">
                <p>Produk ini telah dihapus dari database, tetapi masih tersedia melalui cache. Informasi mungkin tidak up-to-date.</p>
            </div>
        @endif

        <!-- Main Product Card -->
        <div class="product-card">
            <!-- Product Header -->
            <div class="product-header">
                <h2>{{ $product->name ?? 'Produk Tidak Ditemukan' }}</h2>
                <div class="tags">
                    <span class="tag">{{ $product->size ?? 'Ukuran Tidak Tersedia' }}</span>
                    <span class="tag">{{ $product->color ?? 'Warna Tidak Tersedia' }}</span>
                </div>
            </div>

            <!-- Product Content -->
            <div class="product-content">
                <!-- Left Column - QR Code and Product ID -->
                <div>
                    <!-- QR Code Display -->
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(isset($product->id) ? route('inventory.show', $product->id) : url('/')) }}" alt="QR Code untuk produk {{ $product->name ?? '-' }}" aria-label="QR Code untuk produk {{ $product->name ?? '-' }}" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                        <p>QR Code Produk</p>
                    </div>

                    <!-- Product ID -->
                    <div class="product-id">
                        <div class="icon">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <p>Product ID</p>
                            <p><strong>#{{ str_pad($product->id ?? '000', 3, '0', STR_PAD_LEFT) }}</strong></p>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="stock-status">
                        <h3>Status Stok</h3>
                        <div class="status">
                            <span>Unit Aktif</span>
                            <span class="badge {{ ($product->stock ?? 0) < 5 ? 'bg-red-400 text-white' : 'bg-green-400 text-black' }}">
                                {{ $product->stock ?? 0 }}
                            </span>
                        </div>
                        <div class="stock-details">
                            <p class="text-gray-400">Stok Rendah: < 5 unit</p>
                        </div>
                        <div class="progress-bar">
                            <div class="progress" style="width: {{ min((($product->stock ?? 0) / 50) * 100, 100) }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Product Details -->
                <div>
                    <!-- Price Information -->
                    <div class="price-info">
                        <h2>Informasi Harga</h2>
                        <div class="price">
                            <span style="color: #d1d5db;">Harga Jual</span>
                            <span>Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="price">
                            <span style="color: #d1d5db;">Harga Diskon</span>
                            <span>{{ $product->discount_price ? 'Rp ' . number_format($product->discount_price, 0, ',', '.') : '-' }}</span>
                        </div>
                        @auth
                            <div class="price">
                                <span style="color: #d1d5db;">Harga Beli</span>
                                <span>Rp {{ number_format($product->purchase_price ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @endauth
                    </div>

                    <!-- Product Details -->
                    <div class="product-details">
                        <h3>Detail Produk</h3>
                        <div class="info-item">
                            <span style="color: #d1d5db;">Warna</span>
                            <span>{{ $product->color ?? 'Warna Tidak Tersedia' }}</span>
                        </div>
                        <div class="info-item">
                            <span style="color: #d1d5db;">Ukuran</span>
                            <span>{{ $product->size ?? 'Ukuran Tidak Tersedia' }}</span>
                        </div>
                    </div>

                    <!-- Unit List -->
                    <div class="unit-list">
                        <h3>Daftar Unit Aktif</h3>
                        @forelse ($product->productUnits ?? collect([]) as $unit)
                            <div class="unit-item">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode($unit->qr_code ?? '') }}" alt="QR Code untuk unit {{ $unit->unit_code ?? '-' }}" aria-label="QR Code untuk unit {{ $unit->unit_code ?? '-' }}" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                                <div>
                                    <p>Kode Unit: <strong>{{ $unit->unit_code ?? 'Tidak Tersedia' }}</strong></p>
                                    <p>Status: <span class="{{ $unit->is_active ? 'text-green-400' : 'text-red-400' }}">{{ $unit->is_active ? 'Aktif' : 'Non-Aktif' }}</span></p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400">
                                {{ ($product->name ?? '') === 'Produk Tidak Ditemukan' ? 'Produk ini telah dihapus, tidak ada unit tersedia.' : 'Tidak ada unit aktif untuk produk ini.' }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Closing Message -->
        <div class="closing-message">
            <p>Terima kasih telah berkunjung ke Toko Sepatu by Sovan!</p>
            <p>Jangan lupa untuk kembali dan temukan koleksi sepatu terbaik kami.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stockElement = document.querySelector('.bg-red-400');
            if (stockElement) {
                stockElement.parentElement.style.animation = 'pulse 2s infinite';
            }
        });
    </script>
</body>
</html>