<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detail unit {{ $unit->unit_code ?? 'tidak tersedia' }} untuk produk {{ $product->name ?? 'tidak tersedia' }} di Toko Sepatu by Sovan.">
    <title>Detail Unit Produk - Toko Sepatu by Sovan</title>
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

        .unit-card {
            background: #2d2d2d;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        .unit-header {
            background: linear-gradient(to right, #f97316, #ea580c);
            padding: 1.5rem;
            text-align: center;
        }

        .unit-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .unit-header .tags {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .unit-header .tag {
            background: rgba(0, 0, 0, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            color: #000;
        }

        .unit-content {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .unit-content {
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

        .unit-info, .product-info, .similar-products {
            background: #3f3f3f;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .unit-info h3, .product-info h3, .similar-products h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 1rem;
        }

        .unit-info .info-item, .product-info .info-item, .similar-products .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .unit-info .info-item svg, .product-info .info-item svg, .similar-products .info-item svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #f97316;
            margin-right: 0.75rem;
        }

        .unit-info .info-item p, .product-info .info-item p, .similar-products .info-item p {
            color: #d1d5db;
            font-size: 0.875rem;
        }

        .similar-products .info-item a {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
        }

        .similar-products .info-item a:hover {
            text-decoration: underline;
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

            .unit-header h2 {
                font-size: 1.25rem;
            }

            .qr-code img {
                max-width: 7.5rem;
            }
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
            <p>Detail Unit Produk</p>
        </div>

        <!-- Alert jika produk dihapus -->
        @if ($product->name === 'Produk Tidak Ditemukan')
            <div class="alert-deleted">
                <p>Produk ini telah dihapus dari database, tetapi masih tersedia melalui cache. Informasi mungkin tidak up-to-date.</p>
            </div>
        @endif

        <!-- Main Unit Card -->
        <div class="unit-card">
            <!-- Unit Header -->
            <div class="unit-header">
                <h2>{{ $product->name ?? 'Produk Tidak Ditemukan' }}</h2>
                <div class="tags">
                    <span class="tag">Unit: {{ $unit->unit_code ?? 'Tidak Tersedia' }}</span>
                    <span class="tag">{{ $product->size ?? 'Ukuran Tidak Tersedia' }}</span>
                    <span class="tag">{{ $product->color ?? 'Warna Tidak Tersedia' }}</span>
                </div>
            </div>

            <!-- Unit Content -->
            <div class="unit-content">
                <!-- Left Column - QR Code -->
                <div>
                    <!-- QR Code Display -->
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($unit->qr_code) }}" alt="QR Code for Unit {{ $unit->unit_code ?? '-' }}" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                        <p>QR Code Unit</p>
                    </div>
                </div>

                <!-- Right Column - Unit and Product Details -->
                <div>
                    <!-- Unit Information -->
                    <div class="unit-info">
                        <h3>Informasi Unit</h3>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <p>Kode Unit: <strong>{{ $unit->unit_code ?? 'Tidak Tersedia' }}</strong></p>
                        </div>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Status: <span class="{{ $unit->is_active ? 'text-green-400' : 'text-red-400' }}">{{ $unit->is_active ? 'Aktif' : 'Non-Aktif' }}</span></p>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="product-info">
                        <h3>Informasi Produk</h3>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <p>Nama: <strong>{{ $product->name ?? 'Tidak Tersedia' }}</strong></p>
                        </div>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p>Ukuran: {{ $product->size ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <p>Warna: {{ $product->color ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Harga Jual: Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                        </div>
                        @if ($product->discount_price)
                            <div class="info-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Harga Diskon: Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                            </div>
                        @endif
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p>Stok: {{ $product->productUnits->count() ?? 0 }} unit</p>
                        </div>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p><a href="{{ route('inventory.show', $product->id) }}">Lihat Detail Produk</a></p>
                        </div>
                    </div>

                    <!-- Similar Products -->
                    @if (!empty($similarProducts))
                        <div class="similar-products">
                            <h3>Produk Sejenis</h3>
                            @foreach ($similarProducts as $similar)
                                <div class="info-item">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p>
                                        <a href="{{ route('inventory.show', $similar['id']) }}">
                                            {{ $similar['name'] }} (Ukuran: {{ $similar['size'] }}, Warna: {{ $similar['color'] }}, Stok: {{ $similar['stock'] }} unit)
                                        </a>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Closing Message -->
        <div class="closing-message">
            <p>Toko Sepatu by Sovan</p>
            <p>Jl. Raya No.123, Jakarta, Indonesia</p>
        </div>
    </div>
</body>
</html>