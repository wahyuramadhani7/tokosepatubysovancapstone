<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #fef3c7 100%);
            color: #1f2937;
            min-height: 100vh;
            padding-top: 4rem;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            z-index: -1;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(90deg, #10b981, #34d399);
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #059669, #22c55e);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: 0.4s;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .header {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .badge {
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-warning {
            background: rgba(234, 179, 8, 0.2);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #34d399;
        }
        input, select, textarea {
            background: #f9fafb;
            border: 1px solid #6b7280;
            color: #1f2937;
            border-radius: 6px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
            outline: none;
        }
        li:hover {
            background: rgba(16, 185, 129, 0.1);
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 8px;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 50;
        }
        .popup-enter {
            animation: popupIn 0.3s ease-out;
        }
        @keyframes popupIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#10b981',
                            secondary: '#34d399',
                            dark: {
                                50: '#f9fafb',
                                100: '#f3f4f6',
                                200: '#e5e7eb',
                                300: '#d1d5db',
                                400: '#9ca3af',
                                500: '#6b7280',
                                600: '#4b5563',
                                700: '#374151',
                                800: '#1f2937',
                                900: '#111827',
                            },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionApp()" x-init="initialize()">
    <!-- Header -->
    <header class="fixed top-0 w-full header text-gray-900 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-brand-primary rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Montserrat'] font-bold text-xl text-gray-900">Sepatu by Sovan</h1>
                    <p class="text-xs text-gray-600">Premium Footwear</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('transactions.index') }}" class="p-2 bg-brand-dark-100 rounded-lg text-gray-900 hover:bg-brand-primary hover-scale" title="Kembali ke Daftar Transaksi">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="p-2 bg-brand-dark-100 rounded-lg text-gray-900 hover:bg-brand-primary hover-scale" title="Dashboard">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10 max-w-6xl">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-gray-900 p-4 rounded-lg fade-in flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-1.5 mr-3">
                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-8 card rounded-lg p-6 fade-in">
            <h1 class="font-['Montserrat'] text-2xl font-bold text-gray-900 flex items-center">
                <svg class="h-6 w-6 mr-2 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Transaksi Baru
            </h1>
            <p class="text-sm text-gray-600">Pilih unit produk dan lengkapi detail transaksi dengan mudah</p>
        </div>

        <!-- Success/Error Popup Modal -->
        <div x-show="showPopup" class="modal-overlay" x-cloak x-transition:enter="popup-enter" @click.self="closePopup">
            <div class="card rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-gray-900 font-['Montserrat']" x-text="popupTitle"></h3>
                    <button type="button" @click="closePopup" class="text-gray-600 hover:text-brand-primary p-1 rounded-full hover:bg-brand-dark-100">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <svg class="h-12 w-12 mx-auto mb-3" :class="popupType === 'success' ? 'text-brand-primary' : 'text-red-500'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="popupType === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" />
                    </svg>
                    <p class="text-gray-900 text-sm" x-text="popupMessage"></p>
                    <button type="button" @click="closePopup" class="mt-4 btn-primary px-4 py-2 text-sm hover-scale">
                        OK
                    </button>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Product Selection -->
                <div class="card rounded-lg p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-2-4H7a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                        </svg>
                        Pilih Unit Produk
                    </h2>

                    <div class="flex items-center space-x-3 mb-4">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input="searchUnits" placeholder="Cari nama, warna, ukuran, atau kode unit..." class="w-full pl-10 pr-3 py-2 text-sm">
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" @click="openScanner" class="btn-primary px-3 py-2 text-sm flex items-center hover-scale">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                </svg>
                                Scan QR
                            </button>
                            <button type="button" @click="focusHardwareInput" class="btn-primary px-3 py-2 text-sm flex items-center hover-scale">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                </svg>
                                Scan Hardware
                            </button>
                        </div>
                        <input type="text" x-model="qrInput" @input="handleHardwareQrScan" x-ref="qrInput" class="hidden" placeholder="Pindai dengan perangkat keras">
                    </div>

                    <!-- QR Scanner Modal -->
                    <div x-show="isScannerOpen" class="modal-overlay" x-cloak @click.self="closeScanner">
                        <div class="card rounded-lg p-6 w-full max-w-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-base font-semibold text-gray-900 font-['Montserrat']">Scan QR Code Unit</h3>
                                <button type="button" @click="closeScanner" class="text-gray-600 hover:text-brand-primary p-1 rounded-full hover:bg-brand-dark-100">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div id="qr-reader"></div>
                            <p class="text-xs text-gray-600 mt-3 text-center">Arahkan kamera ke kode QR unit produk</p>
                            <p class="text-xs text-red-600 mt-2 text-center" x-text="scanError" x-show="scanError"></p>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="searchResults.length > 0" class="card border rounded-lg mb-4 max-h-48 overflow-y-auto custom-scrollbar fade-in" x-cloak>
                        <ul class="divide-y divide-brand-primary/20">
                            <template x-for="unit in searchResults" :key="unit.unit_code">
                                <li class="p-3 transition-colors cursor-pointer" @click="addToCart(unit)">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm" x-text="unit.product_name"></p>
                                            <div class="flex items-center mt-1 space-x-2">
                                                <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                <span class="text-xs text-gray-600" x-text="unit.color"></span>
                                                <span class="text-gray-400">|</span>
                                                <span class="text-xs text-gray-600" x-text="`Ukuran: ${unit.size}`"></span>
                                                <span class="text-gray-400">|</span>
                                                <span class="text-xs text-gray-600" x-text="`Kode: ${unit.unit_code}`"></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900 text-sm" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                            <button type="button" class="mt-1 text-xs bg-brand-primary text-white py-1 px-2 rounded-full font-semibold hover-scale">+ Tambah</button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Product Units List -->
                    <div class="border border-brand-primary/20 rounded-lg max-h-80 overflow-y-auto custom-scrollbar">
                        <div x-show="availableUnits.length === 0" class="text-center py-10 text-gray-600">
                            <svg class="h-10 w-10 mx-auto text-brand-primary/50 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-sm">Tidak ada unit produk tersedia</p>
                            <p class="text-xs text-gray-600">Coba lagi nanti</p>
                        </div>
                        <ul class="divide-y divide-brand-primary/20">
                            <template x-for="unit in availableUnits" :key="unit.unit_code">
                                <li class="p-3 transition-colors cursor-pointer fade-in" @click="addToCart(unit)">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm" x-text="unit.product_name"></p>
                                            <div class="flex items-center mt-1 space-x-2">
                                                <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                <span class="text-xs text-gray-600" x-text="unit.color"></span>
                                                <span class="text-gray-400">|</span>
                                                <span class="text-xs text-gray-600" x-text="`Ukuran: ${unit.size}`"></span>
                                                <span class="text-gray-400">|</span>
                                                <span class="text-xs text-gray-600" x-text="`Kode: ${unit.unit_code}`"></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900 text-sm" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                            <button type="button" class="mt-1 text-xs bg-brand-primary text-white py-1 px-2 rounded-full font-semibold hover-scale">+ Tambah</button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Customer & Cart -->
                <div class="card rounded-lg p-6 fade-in">
                    <!-- Customer Info -->
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Pelanggan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name') }}" class="w-full px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">No. Telepon</label>
                            <input type="text" name="customer_phone" placeholder="Masukkan nomor telepon" value="{{ old('customer_phone') }}" class="w-full px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <select name="payment_method" id="payment_method" class="w-full px-3 py-2 text-sm" required>
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Pilih metode pembayaran</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>QRIS</option>
                                <option value="debit" {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                            <textarea name="notes" rows="3" placeholder="Catatan tambahan untuk transaksi" class="w-full px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Cart -->
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-t border-brand-primary/20 pt-4">
                        <svg class="h-5 w-5 mr-2 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Keranjang Belanja
                    </h2>

                    <div x-show="cart.length === 0" class="text-center py-10 text-gray-600 border border-brand-primary/20 rounded-lg bg-brand-dark-50/20 mb-4">
                        <svg class="h-12 w-12 mx-auto text-brand-primary/50 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-sm font-medium">Keranjang Kosong</p>
                        <p class="text-xs text-gray-600">Tambahkan unit produk dari daftar di samping</p>
                    </div>

                    <div x-show="cart.length > 0" class="max-h-56 overflow-y-auto custom-scrollbar mb-4 space-y-3">
                        <ul class="space-y-2">
                            <template x-for="(item, index) in cart" :key="index">
                                <li class="border border-brand-primary/20 rounded-lg p-3 bg-brand-dark-50/20 relative fade-in">
                                    <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-600 hover:text-red-600">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <p class="font-medium text-gray-900 pr-6 text-sm" x-text="item.name"></p>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                        <span class="text-xs text-gray-600" x-text="item.color"></span>
                                        <span class="text-gray-400">|</span>
                                        <span class="text-xs text-gray-600" x-text="`Ukuran: ${item.size}`"></span>
                                        <span class="text-gray-400">|</span>
                                        <span class="text-xs text-gray-600" x-text="`Kode: ${item.unit_code}`"></span>
                                    </div>
                                    <div class="flex justify-between items-center mt-3">
                                        <p class="font-medium text-gray-900 text-sm" x-text="item.discount_price ? formatRupiah(item.discount_price) : formatRupiah(item.selling_price)"></p>
                                    </div>
                                    <input type="hidden" :name="'products['+index+'][product_id]'" x-model="item.product_id">
                                    <input type="hidden" :name="'products['+index+'][unit_code]'" x-model="item.unit_code">
                                    <input type="hidden" :name="'products['+index+'][discount_price]'" x-model="item.discount_price">
                                    <input type="hidden" :name="'products['+index+'][quantity]'" value="1">
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Summary -->
                    <div class="border-t border-brand-primary/20 pt-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3 font-['Montserrat']">Ringkasan Pembayaran</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900" x-text="formatRupiah(calculateSubtotal())"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-teal-500">Diskon</span>
                                <span class="font-medium text-teal-500" x-text="formatRupiah(calculateDiscount())"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Harga Baru</span>
                                <div class="flex items-center">
                                    <span class="text-gray-600 mr-2">Rp</span>
                                    <input type="number" name="discount_amount" x-model.number="new_total" min="0" :max="calculateSubtotal()" placeholder="Masukkan harga baru" class="w-24 text-right px-2 py-1 text-sm" @input="updateNewTotal" required>
                                </div>
                            </div>
                            <div class="flex justify-between border-t border-brand-primary/20 pt-3 text-sm">
                                <span class="font-bold text-teal-500">Total Bayar</span>
                                <span class="font-bold text-base text-teal-500" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-5 py-2 btn-primary text-sm flex items-center justify-center hover-scale" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Proses Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        function transactionApp() {
            return {
                availableUnits: @json($availableUnits),
                searchQuery: '',
                searchResults: [],
                cart: [],
                new_total: '',
                isScannerOpen: false,
                qrScanner: null,
                scanError: '',
                qrInput: '',
                showPopup: false,
                popupTitle: '',
                popupMessage: '',
                popupType: 'success',
                scannedUnitCodes: [],

                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
                },

                getColorCode(color) {
                    const colorMap = {
                        'Merah': '#f87171',
                        'Hitam': '#1f2937',
                        'Putih': '#f9fafb',
                        'Biru': '#3b82f6',
                        'Navy': '#1e3a8a',
                        'Hijau': '#10b981',
                        'Kuning': '#fbbf24',
                        'Abu-abu': '#9ca3af',
                        'Coklat': '#92400e',
                        'Pink': '#ec4899',
                        'Ungu': '#8b5cf6',
                        'Orange': '#f97316'
                    };
                    return colorMap[color] || '#9ca3af';
                },

                searchUnits() {
                    this.searchResults = this.searchQuery.trim() ?
                        this.availableUnits.filter(u =>
                            u.product_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            u.color.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            u.size.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            u.unit_code.toLowerCase().includes(this.searchQuery.toLowerCase())
                        ) : [];
                },

                addToCart(unit) {
                    if (this.scannedUnitCodes.includes(unit.unit_code)) {
                        this.popupTitle = 'Unit Sudah Discan';
                        this.popupMessage = `Unit dengan kode "${unit.unit_code}" sudah ada di keranjang.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                        return;
                    }
                    this.cart.push({
                        product_id: unit.product_id,
                        name: unit.product_name,
                        color: unit.color,
                        size: unit.size,
                        selling_price: parseFloat(unit.selling_price) || 0,
                        discount_price: unit.discount_price ? parseFloat(unit.discount_price) : null,
                        unit_code: unit.unit_code
                    });
                    this.scannedUnitCodes.push(unit.unit_code);
                    this.popupTitle = 'Unit Ditambahkan';
                    this.popupMessage = `Unit "${unit.unit_code}" berhasil ditambahkan ke keranjang!`;
                    this.popupType = 'success';
                    this.showPopup = true;
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.qrInput = '';
                    this.updateNewTotal();
                },

                removeItem(index) {
                    const unitCode = this.cart[index].unit_code;
                    this.scannedUnitCodes = this.scannedUnitCodes.filter(code => code !== unitCode);
                    this.cart.splice(index, 1);
                    this.updateNewTotal();
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => {
                        const price = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                        return total + (price || 0);
                    }, 0);
                },

                calculateDiscount() {
                    const subtotal = this.calculateSubtotal();
                    const newTotal = parseFloat(this.new_total) || 0;
                    return Math.max(0, subtotal - newTotal);
                },

                calculateTotal() {
                    return Math.max(0, parseFloat(this.new_total) || 0);
                },

                updateNewTotal() {
                    const subtotal = this.calculateSubtotal();
                    const newTotal = parseFloat(this.new_total) || 0;
                    if (newTotal > subtotal && this.new_total !== '') {
                        this.new_total = subtotal;
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru tidak boleh melebihi subtotal.';
                        this.popupType = 'error';
                        this.showPopup = true;
                    } else if (newTotal < 0) {
                        this.new_total = '';
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru tidak boleh kurang dari 0.';
                        this.popupType = 'error';
                        this.showPopup = true;
                    }
                    this.$refs.qrInput.focus();
                },

                validateForm(event) {
                    if (this.cart.length === 0) {
                        event.preventDefault();
                        this.popupTitle = 'Keranjang Kosong';
                        this.popupMessage = 'Tambahkan unit produk terlebih dahulu.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }
                    if (!document.getElementById('payment_method').value) {
                        event.preventDefault();
                        this.popupTitle = 'Metode Pembayaran Kosong';
                        this.popupMessage = 'Silakan pilih metode pembayaran!';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }
                    if (this.new_total === '' || parseFloat(this.new_total) < 0) {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru harus diisi dan tidak boleh kurang dari 0.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }
                    if (parseFloat(this.new_total) > this.calculateSubtotal()) {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru tidak boleh melebihi subtotal.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }
                    return true;
                },

                openScanner() {
                    this.isScannerOpen = true;
                    this.scanError = '';
                    this.qrInput = '';
                    this.$refs.qrInput.blur();
                    this.$nextTick(() => {
                        try {
                            this.qrScanner = new Html5QrcodeScanner(
                                "qr-reader",
                                { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
                                false
                            );
                            this.qrScanner.render(
                                (decodedText) => this.handleQrScan(decodedText),
                                (error) => {
                                    console.warn('QR scan error:', error);
                                    this.scanError = 'Gagal membaca QR code. Coba lagi.';
                                }
                            );
                        } catch (err) {
                            console.error('Scanner initialization failed:', err);
                            this.scanError = 'Gagal memulai scanner. Periksa izin kamera.';
                            this.isScannerOpen = false;
                        }
                    });
                },

                closeScanner() {
                    if (this.qrScanner) {
                        this.qrScanner.clear().then(() => {
                            this.qrScanner = null;
                        }).catch(err => console.error('Error stopping scanner:', err));
                    }
                    this.isScannerOpen = false;
                    this.scanError = '';
                    this.$refs.qrInput.focus();
                },

                closePopup() {
                    this.showPopup = false;
                    this.popupTitle = '';
                    this.popupMessage = '';
                    this.popupType = 'success';
                    this.$refs.qrInput.focus();
                },

                focusHardwareInput() {
                    this.closeScanner();
                    this.scanError = '';
                    this.qrInput = '';
                    this.$refs.qrInput.focus();
                    this.popupTitle = 'Scanner Fisik Siap';
                    this.popupMessage = 'Pindai kode QR menggunakan perangkat scanner fisik.';
                    this.popupType = 'success';
                    this.showPopup = true;
                },

                async handleQrScan(decodedText) {
                    this.scanError = '';
                    let unitCode;
                    try {
                        const url = new URL(decodedText);
                        const pathSegments = url.pathname.split('/');
                        unitCode = pathSegments[pathSegments.length - 1];
                        if (!unitCode || !unitCode.startsWith('UNIT-')) {
                            throw new Error('Invalid unit code');
                        }
                    } catch (e) {
                        this.scanError = 'QR code tidak valid. Harus berisi URL unit produk.';
                        return;
                    }

                    if (this.scannedUnitCodes.includes(unitCode)) {
                        this.scanError = `Unit dengan kode "${unitCode}" sudah discan.`;
                        return;
                    }

                    try {
                        const response = await fetch(`/transactions/add-product/${unitCode}`, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (!data.success) {
                            this.scanError = data.message;
                            return;
                        }
                        this.addToCart(data.unit);
                        this.closeScanner();
                    } catch (err) {
                        this.scanError = 'Gagal memuat unit produk. Coba lagi.';
                        console.error('Fetch error:', err);
                    }
                },

                async handleHardwareQrScan() {
                    if (!this.qrInput) return;
                    this.scanError = '';
                    let unitCode;
                    try {
                        const url = new URL(this.qrInput);
                        const pathSegments = url.pathname.split('/');
                        unitCode = pathSegments[pathSegments.length - 1];
                        if (!unitCode || !unitCode.startsWith('UNIT-')) {
                            throw new Error('Invalid unit code');
                        }
                    } catch (e) {
                        this.popupTitle = 'QR Code Tidak Valid';
                        this.popupMessage = 'QR code harus berisi URL unit produk yang valid.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        this.qrInput = '';
                        return;
                    }

                    if (this.scannedUnitCodes.includes(unitCode)) {
                        this.popupTitle = 'Unit Sudah Discan';
                        this.popupMessage = `Unit dengan kode "${unitCode}" sudah ada di keranjang.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                        this.qrInput = '';
                        return;
                    }

                    try {
                        const response = await fetch(`/transactions/add-product/${unitCode}`, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (!data.success) {
                            this.popupTitle = 'Gagal Menambahkan Unit';
                            this.popupMessage = data.message;
                            this.popupType = 'error';
                            this.showPopup = true;
                            this.qrInput = '';
                            return;
                        }
                        this.addToCart(data.unit);
                        this.qrInput = '';
                        this.popupTitle = 'Unit Ditambahkan';
                        this.popupMessage = `Unit "${unitCode}" berhasil ditambahkan ke keranjang!`;
                        this.popupType = 'success';
                        this.showPopup = true;
                    } catch (err) {
                        this.popupTitle = 'Gagal Memuat Unit';
                        this.popupMessage = 'Gagal memuat unit produk. Coba lagi.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.error('Fetch error:', err);
                        this.qrInput = '';
                    }
                },

                initialize() {
                    @if(old('products'))
                        this.cart = @json(old('products')).map(item => ({
                            ...item,
                            selling_price: parseFloat(item.selling_price) || 0,
                            discount_price: item.discount_price ? parseFloat(item.discount_price) : null
                        }));
                        this.scannedUnitCodes = this.cart.map(item => item.unit_code);
                    @endif
                    this.new_total = '';
                    this.$refs.qrInput.focus();
                }
            };
        }
    </script>
</body>
</html>