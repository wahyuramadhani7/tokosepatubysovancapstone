<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange-custom': '#FF6B35',
                        'green-custom': '#4ADE80',
                        'gray-dark': '#374151',
                        'gray-medium': '#6B7280'
                    },
                    fontFamily: {
                        'libre-baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Libre Baskerville', serif;
            background: #F3F4F6;
            color: #1F2937;
            min-height: 100vh;
            padding: 1rem;
        }
        .dark body {
            background: #1F2937;
            color: #F3F4F6;
        }
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        .btn-primary {
            background: #FF6B35;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .dark .btn-primary {
            background: #F97316;
        }
        .btn-primary:hover {
            background: #EA580C;
            transform: translateY(-2px);
        }
        .dark .btn-primary:hover {
            background: #FF6B35;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            transition: transform 0.2s ease;
        }
        .dark .card {
            background: #1F2937;
            border: 1px solid #4B5563;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #E5E7EB;
            border-radius: 8px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 8px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #9CA3AF;
        }
        input, select, textarea {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            color: #1F2937;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.2s ease;
            font-family: 'Libre Baskerville', serif;
        }
        .dark input, .dark select, .dark textarea {
            background: #374151;
            border: 1px solid #6B7280;
            color: #F3F4F6;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 6px rgba(255, 107, 53, 0.4);
            outline: none;
        }
        .dark input:focus, .dark select:focus, .dark textarea:focus {
            border-color: #F97316;
        }
        .modal-overlay, .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 0.5rem;
            border: 2px solid #FF6B35;
        }
        .dark #qr-reader {
            border: 2px solid #F97316;
        }
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
        @media (max-width: 640px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .grid {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }
            .btn-primary {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            input, select, textarea {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            .card {
                padding: 1rem;
            }
            h1 {
                font-size: 1.5rem;
            }
            h2 {
                font-size: 1.125rem;
            }
            #qr-reader {
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionApp()" x-init="initialize()">
    <!-- Popup Notification -->
    <div x-show="showFeaturePopup" class="popup-overlay" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-1" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-1" x-transition:leave-end="opacity-0">
        <div class="card p-4 max-w-md">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pembaruan Fitur</h2>
                <button @click="closeFeaturePopup" class="text-gray-medium dark:text-gray-400 hover:text-orange-custom p-1.5 rounded-full" aria-label="Tutup pemberitahuan">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <p class="text-base text-gray-700 dark:text-gray-300">
                Kini, Anda dapat memasukkan harga baru keseluruhan untuk transaksi di Ringkasan Pembayaran.
            </p>
            <div class="mt-3 flex justify-end">
                <button @click="closeFeaturePopup" class="btn-primary px-4 py-2 text-base flex items-center">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-gray-900 dark:text-gray-100 p-4 rounded-lg fade-in flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-base">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Buat Transaksi Baru</h1>
                <a href="{{ route('transactions.index') }}" class="bg-orange-custom text-white p-2 rounded-lg hover-scale">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
            <p class="text-gray-medium dark:text-gray-400 mb-6">Pilih unit produk premium dengan pengalaman transaksi yang elegan</p>
        </div>

        <!-- Success/Error Popup Modal -->
        <div x-show="showPopup" class="modal-overlay" x-cloak x-transition:enter="fade-in" @click.self="closePopup">
            <div class="card rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="popupTitle"></h3>
                    <button type="button" @click="closePopup" class="text-gray-medium dark:text-gray-400 hover:text-orange-custom p-2 rounded-full hover-scale">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto mb-4" :class="popupType === 'success' ? 'text-green-custom' : 'text-red-500'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="popupType === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" />
                    </svg>
                    <p class="text-gray-900 dark:text-gray-100 text-base" x-text="popupMessage"></p>
                    <button type="button" @click="closePopup" class="mt-6 bg-orange-custom text-white px-4 py-2 rounded-lg font-medium hover:bg-orange-600 hover-scale">
                        OK
                    </button>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product Selection -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Scan Options -->
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-[#292929] rounded-lg p-6 text-white hover-scale">
                            <h3 class="text-lg font-semibold mb-4">Scan Kamera</h3>
                            <div class="flex justify-center">
                                <div class="bg-gray-300 rounded-lg p-8" x-show="!isScannerOpen">
                                    <svg class="w-16 h-16 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z M8 7a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2H8z" />
                                    </svg>
                                </div>
                                <div x-show="isScannerOpen" class="w-full">
                                    <div id="qr-reader"></div>
                                    <p class="text-sm text-gray-100 mt-4 text-center">Arahkan kamera ke kode QR unit produk</p>
                                    <p class="text-sm text-red-400 mt-3 text-center" x-text="scanError" x-show="scanError"></p>
                                </div>
                            </div>
                            <button type="button" @click="openScanner" x-show="!isScannerOpen" class="mt-4 w-full bg-orange-custom text-white px-4 py-2 rounded-lg font-medium hover:bg-orange-600 hover-scale">Buka Scanner</button>
                            <button type="button" @click="closeScanner" x-show="isScannerOpen" class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 hover-scale">Tutup Scanner</button>
                        </div>
                        <div>
                            <button type="button" @click="showManualSelection = !showManualSelection" class="w-full bg-orange-custom text-white px-4 py-2 rounded-lg font-medium hover:bg-orange-600 hover-scale">
                                <span x-text="showManualSelection ? 'Tutup Pilihan Manual' : 'Pilih Unit Manual'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="searchResults.length > 0" class="card rounded-lg border border-orange-300 mb-6 max-h-96 overflow-y-auto custom-scrollbar fade-in" x-cloak>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                            <template x-for="unit in searchResults" :key="unit.unit_code">
                                <li class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 hover-scale">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 text-base" x-text="unit.product_name"></p>
                                            <div class="flex items-center mt-1 space-x-2 text-sm text-gray-medium dark:text-gray-400">
                                                <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                <span x-text="`${unit.color}, Ukuran: ${unit.size}, Kode: ${unit.unit_code}`"></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 text-base" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                            <button type="button" @click="addToCart(unit)" class="mt-1 text-sm bg-orange-custom text-white py-1 px-2 rounded-lg font-semibold hover:bg-orange-600 hover-scale">+ Tambah</button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Product Units List -->
                    <div x-show="showManualSelection" class="card rounded-lg border border-orange-300" x-cloak>
                        <div class="bg-orange-custom text-white p-4 rounded-t-lg">
                            <h3 class="text-lg font-semibold">Pilih Unit Produk</h3>
                            <div class="mt-2">
                                <input type="text" x-model.debounce.500="searchQuery" @input="searchUnits" placeholder="Cari nama, warna, ukuran, atau kode unit..." class="w-full bg-gray-600 text-white px-3 py-2 rounded text-sm">
                            </div>
                        </div>
                        <div class="p-4">
                            <div x-show="paginatedUnits.length === 0 && !searchQuery" class="text-center py-12 text-gray-medium dark:text-gray-400">
                                <svg class="h-12 w-12 mx-auto text-gray-medium dark:text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-base font-medium">Tidak ada unit produk tersedia</p>
                                <p class="text-sm text-gray-medium dark:text-gray-400">Coba lagi nanti</p>
                            </div>
                            <ul class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar" x-on:scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 50 && hasMoreUnits) loadMore()">
                                <template x-for="unit in paginatedUnits" :key="unit.unit_code">
                                    <li class="border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 hover-scale fade-in">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-base" x-text="unit.product_name"></p>
                                                <div class="flex items-center mt-1 space-x-2 text-sm text-gray-medium dark:text-gray-400">
                                                    <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                    <span x-text="`${unit.color}, Ukuran: ${unit.size}, Kode: ${unit.unit_code}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-base" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                                <button type="button" @click="addToCart(unit)" class="mt-1 text-sm bg-orange-custom text-white py-1 px-2 rounded-lg font-semibold hover:bg-orange-600 hover-scale">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Customer & Cart -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="card rounded-lg p-6 border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Informasi Pelanggan</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name') }}" class="w-full px-3 py-2 text-base rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Masukkan nomor telepon" value="{{ old('customer_phone') }}" class="w-full px-3 py-2 text-base rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Pembayaran <span class="text-red-600 dark:text-red-400">*</span></label>
                                <select name="payment_method" id="payment_method" x-model="paymentMethod" @change="updateCardTypeVisibility" class="w-full px-3 py-2 text-base rounded-lg" required>
                                    <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Pilih metode pembayaran</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="debit" {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                </select>
                            </div>
                            <div x-show="paymentMethod === 'debit'" x-cloak>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Kartu <span class="text-red-600 dark:text-red-400">*</span></label>
                                <select name="card_type" id="card_type" class="w-full px-3 py-2 text-base rounded-lg" x-model="cardType">
                                    <option value="" disabled {{ old('card_type') ? '' : 'selected' }}>Pilih tipe kartu</option>
                                    <option value="Mandiri" {{ old('card_type') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    <option value="BRI" {{ old('card_type') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                    <option value="BCA" {{ old('card_type') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea name="notes" rows="3" placeholder="Catatan tambahan untuk transaksi" class="w-full px-3 py-2 text-base rounded-lg">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Cart -->
                    <div class="card rounded-lg p-6 border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Keranjang Belanja</h3>
                        <div x-show="cart.length === 0" class="text-center py-8">
                            <svg class="h-16 w-16 mx-auto text-gray-medium dark:text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Keranjang Kosong</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Tambahkan unit produk dari daftar di samping</p>
                        </div>
                        <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto custom-scrollbar mb-6 space-y-3">
                            <ul class="space-y-3">
                                <template x-for="(item, index) in cart" :key="index">
                                    <li class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700 relative fade-in hover-scale">
                                        <button type="button" @click="removeItem(index)" class="absolute top-3 right-3 text-gray-medium dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover-scale">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100 pr-8 text-base" x-text="item.name"></p>
                                        <div class="flex items-center mt-1 space-x-2 text-sm text-gray-medium dark:text-gray-400">
                                            <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                            <span x-text="`${item.color}, Ukuran: ${item.size}, Kode: ${item.unit_code}`"></span>
                                        </div>
                                        <div class="mt-4 space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-medium dark:text-gray-400">Harga Asli:</span>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-base" x-text="item.discount_price ? formatRupiah(item.discount_price) : formatRupiah(item.selling_price)"></p>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-medium dark:text-gray-400">Harga Baru:</span>
                                                <input type="number" :name="'products['+index+'][new_price]'" x-model.number="item.new_price" min="0" :max="item.discount_price || item.selling_price" @input="validateNewPrice(index)" placeholder="Harga baru" class="w-28 text-right px-3 py-2 text-base rounded-lg">
                                            </div>
                                            <div class="flex justify-between items-center" x-show="item.new_price !== null && item.new_price !== '' && item.new_price >= 0">
                                                <span class="text-gray-medium dark:text-gray-400">Diskon:</span>
                                                <p class="font-semibold text-green-custom" x-text="formatRupiah(calculateItemDiscount(item))"></p>
                                            </div>
                                        </div>
                                        <input type="hidden" :name="'products['+index+'][product_id]'" x-model="item.product_id">
                                        <input type="hidden" :name="'products['+index+'][unit_code]'" x-model="item.unit_code">
                                        <input type="hidden" :name="'products['+index+'][discount_price]'" x-model="item.discount_price || item.selling_price">
                                        <input type="hidden" :name="'products['+index+'][quantity]'" value="1">
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="card rounded-lg p-6 border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Ringkasan Pembayaran</h3>
                        <div x-show="cart.length === 0" class="text-center py-8">
                            <svg class="h-16 w-16 mx-auto text-gray-medium dark:text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Ringkasan Kosong</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Tambahkan unit produk terlebih dahulu</p>
                        </div>
                        <div x-show="cart.length > 0" class="space-y-3">
                            <div class="flex justify-between text-base">
                                <span class="text-gray-medium dark:text-gray-400">Subtotal</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="formatRupiah(calculateSubtotal())"></span>
                            </div>
                            <div class="flex justify-between text-base">
                                <span class="text-gray-medium dark:text-gray-400">Harga Baru Keseluruhan</span>
                                <input type="number" name="overall_new_price" x-model.number="overallNewPrice" min="0" :max="calculateSubtotal()" @input="validateOverallNewPrice" placeholder="Harga baru keseluruhan" class="w-32 text-right px-3 py-2 text-base rounded-lg">
                            </div>
                            <div class="flex justify-between text-base">
                                <span class="text-gray-medium dark:text-gray-400">Diskon</span>
                                <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="formatRupiah(calculateDiscount())"></span>
                            </div>
                            <hr class="my-2 border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-900 dark:text-gray-100">Total Bayar</span>
                                <span class="text-gray-900 dark:text-gray-100" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                            <button type="submit" class="w-full mt-6 bg-green-custom text-white py-3 px-4 rounded-lg font-medium hover:bg-green-600 hover-scale" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
                                Proses Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        function transactionApp() {
            return {
                availableUnits: @json($availableUnits),
                paginatedUnits: [],
                currentPage: 1,
                itemsPerPage: 20,
                searchQuery: '',
                searchResults: [],
                cart: [],
                paymentMethod: '',
                cardType: '',
                isScannerOpen: false,
                qrScanner: null,
                scanError: '',
                showPopup: false,
                popupTitle: '',
                popupMessage: '',
                popupType: 'success',
                scannedUnitCodes: [],
                showManualSelection: false,
                showFeaturePopup: false,
                overallNewPrice: null,

                init() {
                    this.darkMode = localStorage.getItem('darkMode') === 'true';
                    this.$watch('darkMode', value => {
                        localStorage.setItem('darkMode', value);
                    });

                    // Show feature update popup only once per session
                    const hasSeenPopup = sessionStorage.getItem('hasSeenFeaturePopup');
                    if (!hasSeenPopup) {
                        this.showFeaturePopup = true;
                        sessionStorage.setItem('hasSeenFeaturePopup', 'true');
                        setTimeout(() => {
                            this.closeFeaturePopup();
                        }, 30000); // Close after 30 seconds
                    }

                    this.initialize();
                },

                initialize() {
                    this.loadPaginatedUnits();
                    @if(old('products'))
                        this.cart = @json(old('products')).map(item => ({
                            ...item,
                            selling_price: parseFloat(item.selling_price) || 0,
                            discount_price: item.discount_price ? parseFloat(item.discount_price) : null,
                            new_price: item.new_price ? parseFloat(item.new_price) : null
                        }));
                        this.scannedUnitCodes = this.cart.map(item => item.unit_code);
                    @endif
                    this.paymentMethod = '{{ old('payment_method') }}';
                    this.cardType = '{{ old('card_type') }}';
                    this.overallNewPrice = '{{ old('overall_new_price') }}' ? parseFloat('{{ old('overall_new_price') }}') : null;
                },

                closeFeaturePopup() {
                    this.showFeaturePopup = false;
                },

                loadPaginatedUnits() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    this.paginatedUnits = this.availableUnits.slice(0, end);
                },

                get hasMoreUnits() {
                    return this.paginatedUnits.length < this.availableUnits.length;
                },

                loadMore() {
                    this.currentPage++;
                    this.loadPaginatedUnits();
                },

                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
                },

                getColorCode(color) {
                    const colorMap = {
                        'Merah': '#DC2626',
                        'Hitam': '#292929',
                        'Putih': '#FAFAFA',
                        'Biru': '#2563EB',
                        'Navy': '#1E3A8A',
                        'Hijau': '#065F46',
                        'Kuning': '#FACC15',
                        'Abu-abu': '#6B7280',
                        'Coklat': '#5D2E0B',
                        'Pink': '#EC4899',
                        'Ungu': '#7C3AED',
                        'Orange': '#EA580C'
                    };
                    return colorMap[color] || '#6B7280';
                },

                searchUnits() {
                    if (!this.searchQuery.trim()) {
                        this.searchResults = [];
                        return;
                    }
                    const query = this.searchQuery.toLowerCase().trim();
                    this.searchResults = this.availableUnits
                        .filter(unit => {
                            return (
                                (unit.product_name && unit.product_name.toLowerCase().includes(query)) ||
                                (unit.color && unit.color.toLowerCase().includes(query)) ||
                                (unit.size && unit.size.toLowerCase().includes(query)) ||
                                (unit.unit_code && unit.unit_code.toLowerCase().includes(query))
                            );
                        })
                        .slice(0, 20);
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
                        unit_code: unit.unit_code,
                        new_price: null
                    });
                    this.scannedUnitCodes.push(unit.unit_code);
                    this.popupTitle = 'Unit Ditambahkan';
                    this.popupMessage = `Unit "${unit.unit_code}" berhasil ditambahkan ke keranjang!`;
                    this.popupType = 'success';
                    this.showPopup = true;
                    this.searchQuery = '';
                    this.searchResults = [];
                },

                removeItem(index) {
                    const unitCode = this.cart[index].unit_code;
                    this.scannedUnitCodes = this.scannedUnitCodes.filter(code => code !== unitCode);
                    this.cart.splice(index, 1);
                    this.overallNewPrice = null; // Reset overall new price when cart changes
                },

                calculateItemDiscount(item) {
                    if (item.new_price === null || item.new_price === '' || item.new_price < 0) return 0;
                    const originalPrice = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                    const newPrice = parseFloat(item.new_price) || originalPrice;
                    return originalPrice - newPrice;
                },

                validateNewPrice(index) {
                    const item = this.cart[index];
                    const maxPrice = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                    const newPrice = parseFloat(item.new_price) || 0;

                    if (newPrice > maxPrice && item.new_price !== null && item.new_price !== '') {
                        this.cart[index].new_price = maxPrice;
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = `Harga baru untuk "${item.name}" tidak boleh melebihi ${this.formatRupiah(maxPrice)}.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                    } else if (newPrice < 0) {
                        this.cart[index].new_price = null;
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = `Harga baru untuk "${item.name}" tidak boleh kurang dari 0.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                    }
                    this.overallNewPrice = null; // Reset overall new price if per-item price changes
                },

                validateOverallNewPrice() {
                    const subtotal = this.calculateSubtotal();
                    const newPrice = parseFloat(this.overallNewPrice) || 0;

                    if (newPrice > subtotal && this.overallNewPrice !== null && this.overallNewPrice !== '') {
                        this.overallNewPrice = subtotal;
                        this.popupTitle = 'Harga Baru Keseluruhan Tidak Valid';
                        this.popupMessage = `Harga baru keseluruhan tidak boleh melebihi subtotal ${this.formatRupiah(subtotal)}.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                    } else if (newPrice < 0) {
                        this.overallNewPrice = null;
                        this.popupTitle = 'Harga Baru Keseluruhan Tidak Valid';
                        this.popupMessage = `Harga baru keseluruhan tidak boleh kurang dari 0.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                    }
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => {
                        const price = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                        return total + (price || 0);
                    }, 0);
                },

                calculateDiscount() {
                    if (this.overallNewPrice !== null && this.overallNewPrice !== '' && this.overallNewPrice >= 0) {
                        return this.calculateSubtotal() - parseFloat(this.overallNewPrice);
                    }
                    return this.cart.reduce((total, item) => {
                        if (item.new_price === null || item.new_price === '' || item.new_price < 0) return total;
                        const originalPrice = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                        const newPrice = parseFloat(item.new_price) || originalPrice;
                        return total + (originalPrice - newPrice);
                    }, 0);
                },

                calculateTotal() {
                    if (this.overallNewPrice !== null && this.overallNewPrice !== '' && this.overallNewPrice >= 0) {
                        return parseFloat(this.overallNewPrice) || 0;
                    }
                    return this.cart.reduce((total, item) => {
                        const price = parseFloat(item.new_price) || (item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price));
                        return total + (price || 0);
                    }, 0);
                },

                updateCardTypeVisibility() {
                    if (this.paymentMethod !== 'debit') {
                        this.cardType = '';
                    }
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

                    if (!this.paymentMethod) {
                        event.preventDefault();
                        this.popupTitle = 'Metode Pembayaran Kosong';
                        this.popupMessage = 'Silakan pilih metode pembayaran!';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }

                    if (this.paymentMethod === 'debit' && !this.cardType) {
                        event.preventDefault();
                        this.popupTitle = 'Tipe Kartu Kosong';
                        this.popupMessage = 'Silakan pilih tipe kartu untuk metode pembayaran Debit!';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }

                    const subtotal = this.calculateSubtotal();
                    const overallNewPrice = parseFloat(this.overallNewPrice) || 0;
                    if (overallNewPrice > subtotal && this.overallNewPrice !== null && this.overallNewPrice !== '') {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Keseluruhan Tidak Valid';
                        this.popupMessage = `Harga baru keseluruhan tidak boleh melebihi subtotal ${this.formatRupiah(subtotal)}.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }

                    if (overallNewPrice < 0) {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Keseluruhan Tidak Valid';
                        this.popupMessage = 'Harga baru keseluruhan tidak boleh kurang dari 0.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        return false;
                    }

                    for (let i = 0; i < this.cart.length; i++) {
                        const item = this.cart[i];
                        const maxPrice = item.discount_price !== null && item.discount_price !== undefined ? parseFloat(item.discount_price) : parseFloat(item.selling_price);
                        const newPrice = parseFloat(item.new_price) || 0;

                        if (item.new_price !== null && item.new_price !== '' && newPrice > maxPrice) {
                            event.preventDefault();
                            this.popupTitle = 'Harga Baru Tidak Valid';
                            this.popupMessage = `Harga baru untuk "${item.name}" tidak boleh melebihi ${this.formatRupiah(maxPrice)}.`;
                            this.popupType = 'error';
                            this.showPopup = true;
                            return false;
                        }

                        if (item.new_price !== null && item.new_price !== '' && newPrice < 0) {
                            event.preventDefault();
                            this.popupTitle = 'Harga Baru Tidak Valid';
                            this.popupMessage = `Harga baru untuk "${item.name}" tidak boleh kurang dari 0.`;
                            this.popupType = 'error';
                            this.showPopup = true;
                            return false;
                        }
                    }

                    return true;
                },

                openScanner() {
                    this.isScannerOpen = true;
                    this.scanError = '';
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
                },

                closePopup() {
                    this.showPopup = false;
                    this.popupTitle = '';
                    this.popupMessage = '';
                    this.popupType = 'success';
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
                }
            };
        }
    </script>
</body>
</html>