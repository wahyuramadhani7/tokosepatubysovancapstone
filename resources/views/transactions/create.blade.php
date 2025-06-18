<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #1a202c 100%), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            background-blend-mode: overlay;
            color: #e2e8f0;
            min-height: 100vh;
            padding-top: 5rem;
            position: relative;
            font-size: 14px;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 32, 44, 0.7);
            z-index: -1;
        }
        .hover-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 209, 197, 0.3);
            transition: all 0.3s ease;
        }
        .btn-futuristic {
            background: linear-gradient(90deg, #4fd1c5, #81e6d9);
            color: #1a202c;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 8px;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            font-size: 12px;
        }
        .btn-futuristic:hover {
            background-position: 100%;
            border-color: #4fd1c5;
            transform: translateY(-2px);
        }
        .btn-futuristic::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .btn-futuristic:hover::after {
            left: 100%;
        }
        .bg-futuristic-header {
            background: linear-gradient(180deg, rgba(26, 32, 44, 0.95), rgba(79, 209, 197, 0.1));
            border-bottom: 1px solid #4fd1c5;
        }
        .card-futuristic {
            background: rgba(45, 55, 72, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(79, 209, 197, 0.2);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card-futuristic:hover {
            transform: translateY(-3px);
            border-color: rgba(79, 209, 197, 0.4);
        }
        .status-badge {
            padding: 0.4rem 1.2rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 11px;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .status-badge.bg-teal-900\/50 {
            background: rgba(79, 209, 197, 0.5);
            color: #e6fffa;
            border-color: rgba(79, 209, 197, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(45, 55, 72, 0.5);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4fd1c5;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #81e6d9;
        }
        input, select, textarea {
            background: rgba(45, 55, 72, 0.8);
            border: 1px solid rgba(79, 209, 197, 0.3);
            color: #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 12px;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #4fd1c5;
            box-shadow: 0 0 5px rgba(79, 209, 197, 0.5);
        }
        li:hover {
            background: rgba(79, 209, 197, 0.1);
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 12px;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(26, 32, 44, 0.9);
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
                            neon: {
                                teal: '#4fd1c5',
                                light: '#e6fffa',
                            },
                            dark: {
                                50: '#f7fafc',
                                100: '#edf2f7',
                                200: '#e2e8f0',
                                300: '#cbd5e0',
                                400: '#a0aec0',
                                500: '#718096',
                                600: '#4a5568',
                                700: '#2d3748',
                                800: '#1a202c',
                                900: '#171923',
                            },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionApp()" x-init="initialize()">
    <div class="flex flex-col">
        <!-- Header -->
        <header class="fixed top-0 w-full bg-futuristic-header text-white z-50">
            <div class="container mx-auto px-6 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-brand-neon-teal rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-['Orbitron'] font-bold text-xl tracking-tight text-brand-neon-light">Sepatu by Sovan</h1>
                        <p class="text-xs text-gray-400 font-light">Luxury Footwear Collection</p>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="p-2 bg-brand-dark-700 rounded-full text-brand-neon-light hover-glow" title="Kembali">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-12 max-w-7xl">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border border-red-300/20 text-white p-4 rounded-lg slide-in">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-medium text-sm">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-sm text-red-200">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-8 card-futuristic rounded-xl p-6 border slide-in">
                <h1 class="font-['Orbitron'] text-2xl font-bold text-white mb-2 flex items-center">
                    <svg class="h-8 w-8 mr-3 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Transaksi Baru
                </h1>
                <p class="text-sm text-gray-400">Pilih unit produk dan lengkapi detail transaksi dengan gaya dan kemudahan</p>
            </div>

            <!-- Success/Error Popup Modal -->
            <div x-show="showPopup" class="modal-overlay" x-cloak x-transition:enter="popup-enter" @click.self="closePopup">
                <div class="card-futuristic rounded-xl p-6 w-full max-w-md border">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-semibold text-white font-['Orbitron']" x-text="popupTitle"></h3>
                        <button type="button" @click="closePopup" class="text-gray-400 hover:text-brand-neon-teal p-1 rounded-full hover:bg-brand-dark-700 transition-colors">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="text-center">
                        <svg class="h-12 w-12 mx-auto mb-3" :class="popupType === 'success' ? 'text-brand-neon-teal' : 'text-red-300'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="popupType === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" />
                        </svg>
                        <p class="text-sm text-white" x-text="popupMessage"></p>
                        <button type="button" @click="closePopup" class="mt-4 btn-futuristic px-4 py-2 rounded-lg text-xs font-semibold hover-glow">
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
                    <div class="card-futuristic rounded-xl p-6 border slide-in">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-2-4H7a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                            </svg>
                            Pilih Unit Produk
                        </h2>

                        <div class="flex items-center space-x-3 mb-4">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" x-model="searchQuery" @input="searchUnits" placeholder="Cari nama, warna, ukuran, atau kode unit..." class="w-full pl-10 pr-3 py-2 text-xs">
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" @click="openScanner" class="btn-futuristic px-3 py-2 rounded-lg flex items-center text-xs font-semibold hover-glow">
                                    <svg class="h-4 w-4 mr-1 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                    </svg>
                                    Scan QR
                                </button>
                                <button type="button" @click="focusHardwareInput" class="btn-futuristic px-3 py-2 rounded-lg flex items-center text-xs font-semibold hover-glow">
                                    <svg class="h-4 w-4 mr-1 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                    </svg>
                                    Scan Hardware
                                </button>
                            </div>
                            <input type="text" x-model="qrInput" @change="handleHardwareQrScan" x-ref="qrInput" class="hidden" placeholder="Pindai dengan perangkat keras">
                        </div>

                        <!-- QR Scanner Modal -->
                        <div x-show="isScannerOpen" class="modal-overlay" x-cloak @click.self="closeScanner">
                            <div class="card-futuristic rounded-xl p-6 w-full max-w-lg border">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-base font-semibold text-white font-['Orbitron']">Scan QR Code Unit</h3>
                                    <button type="button" @click="closeScanner" class="text-gray-400 hover:text-brand-neon-teal p-1 rounded-full hover:bg-brand-dark-700 transition-colors">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div id="qr-reader"></div>
                                <p class="text-xs text-gray-400 mt-3 text-center">Arahkan kamera ke kode QR unit produk</p>
                                <p class="text-xs text-red-300 mt-2 text-center" x-text="scanError" x-show="scanError"></p>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div x-show="searchResults.length > 0" class="card-futuristic border rounded-xl mb-4 max-h-48 overflow-y-auto custom-scrollbar slide-in" x-cloak>
                            <ul class="divide-y divide-brand-neon-teal/20">
                                <template x-for="unit in searchResults" :key="unit.unit_code">
                                    <li class="p-3 transition-colors cursor-pointer" @click="addToCart(unit)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-white text-xs" x-text="unit.product_name"></p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                    <span class="text-xs text-gray-400" x-text="unit.color"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Ukuran: ${unit.size}`"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Kode: ${unit.unit_code}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-white text-xs" x-text="formatRupiah(unit.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-brand-neon-teal text-brand-dark-900 py-1 px-2 rounded-full transition-colors font-semibold hover-glow">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Product Units List -->
                        <div class="border border-brand-neon-teal/20 rounded-xl max-h-80 overflow-y-auto custom-scrollbar">
                            <div x-show="availableUnits.length === 0" class="text-center py-10 text-gray-400">
                                <svg class="h-10 w-10 mx-auto text-brand-neon-teal/50 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-sm">Tidak ada unit produk tersedia</p>
                                <p class="text-xs text-gray-400">Coba lagi nanti</p>
                            </div>
                            <ul class="divide-y divide-brand-neon-teal/20">
                                <template x-for="unit in availableUnits" :key="unit.unit_code">
                                    <li class="p-3 transition-colors cursor-pointer slide-in" @click="addToCart(unit)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-white text-xs" x-text="unit.product_name"></p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                    <span class="text-xs text-gray-400" x-text="unit.color"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Ukuran: ${unit.size}`"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Kode: ${unit.unit_code}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-white text-xs" x-text="formatRupiah(unit.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-brand-neon-teal text-brand-dark-900 py-1 px-2 rounded-full transition-colors font-semibold hover-glow">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Customer & Cart -->
                    <div class="card-futuristic rounded-xl p-6 border slide-in">
                        <!-- Customer Info -->
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name') }}" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Masukkan nomor telepon" value="{{ old('customer_phone') }}" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1">Email</label>
                                <input type="email" name="customer_email" placeholder="email@example.com" value="{{ old('customer_email') }}" class="w-full px-3 py-2 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1">Metode Pembayaran <span class="text-red-300">*</span></label>
                                <select name="payment_method" id="payment_method" class="w-full px-3 py-2 text-xs" required>
                                    <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Pilih metode pembayaran</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-400 mb-1">Catatan</label>
                                <textarea name="notes" rows="3" placeholder="Catatan tambahan untuk transaksi" class="w-full px-3 py-2 text-xs">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Cart -->
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center border-t border-brand-neon-teal/20 pt-4">
                            <svg class="h-5 w-5 mr-2 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Keranjang Belanja
                        </h2>

                        <div x-show="cart.length === 0" class="text-center py-10 text-gray-400 border border-brand-neon-teal/20 rounded-xl bg-brand-dark-800/60 mb-4">
                            <svg class="h-12 w-12 mx-auto text-brand-neon-teal/50 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-sm font-medium">Keranjang Kosong</p>
                            <p class="text-xs text-gray-400">Tambahkan unit produk dari daftar di samping</p>
                        </div>

                        <div x-show="cart.length > 0" class="max-h-56 overflow-y-auto custom-scrollbar mb-4 space-y-3">
                            <ul class="space-y-2">
                                <template x-for="(item, index) in cart" :key="index">
                                    <li class="border border-brand-neon-teal/20 rounded-xl p-3 bg-brand-dark-800/60 relative slide-in">
                                        <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-300 transition-colors">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <p class="font-medium text-white pr-6 text-xs" x-text="item.name"></p>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                            <span class="text-xs text-gray-400" x-text="item.color"></span>
                                            <span class="text-gray-500">|</span>
                                            <span class="text-xs text-gray-400" x-text="`Ukuran: ${item.size}`"></span>
                                            <span class="text-gray-500">|</span>
                                            <span class="text-xs text-gray-400" x-text="`Kode: ${item.unit_code}`"></span>
                                        </div>
                                        <div class="flex justify-between items-center mt-3">
                                            <p class="font-medium text-white text-xs" x-text="formatRupiah(item.selling_price)"></p>
                                        </div>
                                        <input type="hidden" :name="'products['+index+'][product_id]'" x-model="item.product_id">
                                        <input type="hidden" :name="'products['+index+'][unit_code]'" x-model="item.unit_code">
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-brand-neon-teal/20 pt-4">
                            <h2 class="text-lg font-semibold text-white mb-3 font-['Orbitron']">Ringkasan Pembayaran</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Subtotal</span>
                                    <span class="font-medium text-white" x-text="formatRupiah(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Diskon</span>
                                    <div class="flex items-center">
                                        <span class="text-gray-400 mr-2">Rp</span>
                                        <input type="number" name="discount_amount" x-model="discount" min="0" value="{{ old('discount_amount', 0) }}" class="w-20 text-right px-2 py-1 text-xs">
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-brand-neon-teal/20 pt-3 text-sm">
                                    <span class="font-bold text-white">Total Bayar</span>
                                    <span class="font-bold text-base text-brand-neon-teal" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-5 py-2 btn-futuristic rounded-lg font-semibold transition-all flex items-center justify-center text-xs hover-glow" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
                                <svg class="h-5 w-5 mr-2 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Proses Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        function transactionApp() {
            return {
                availableUnits: @json($availableUnits),
                searchQuery: '',
                searchResults: [],
                cart: [],
                discount: {{ old('discount_amount', 0) }},
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
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
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
                        this.popupTitle = 'Unit Sudah Ada';
                        this.popupMessage = `Unit "${unit.unit_code}" sudah ada di keranjang.`;
                        this.popupType = 'error';
                        this.showPopup = true;
                        return;
                    }
                    this.cart.push({
                        product_id: unit.product_id,
                        name: unit.product_name,
                        color: unit.color,
                        size: unit.size,
                        selling_price: unit.selling_price,
                        unit_code: unit.unit_code,
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
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => total + item.selling_price, 0);
                },

                calculateTotal() {
                    return Math.max(0, this.calculateSubtotal() - this.discount);
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
                    return true;
                },

                openScanner() {
                    this.isScannerOpen = true;
                    this.scanError = '';
                    this.qrInput = '';
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

                focusHardwareInput() {
                    this.closeScanner();
                    this.scanError = '';
                    this.qrInput = '';
                    this.$refs.qrInput.focus();
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
                        this.scanError = 'Unit ini sudah discan sebelumnya.';
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
                        this.scanError = 'QR code tidak valid. Harus berisi URL unit produk.';
                        this.qrInput = '';
                        return;
                    }

                    if (this.scannedUnitCodes.includes(unitCode)) {
                        this.scanError = 'Unit ini sudah discan sebelumnya.';
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
                            this.scanError = data.message;
                            this.qrInput = '';
                            return;
                        }
                        this.addToCart(data.unit);
                        this.qrInput = '';
                    } catch (err) {
                        this.scanError = 'Gagal memuat unit produk. Coba lagi.';
                        console.error('Fetch error:', err);
                        this.qrInput = '';
                    }
                },

                initialize() {
                    // Restore cart from old input if available
                    @if(old('products'))
                        this.cart = @json(old('products'));
                        this.scannedUnitCodes = this.cart.map(item => item.unit_code);
                    @endif
                }
            };
        }
    </script>
</body>
</html>