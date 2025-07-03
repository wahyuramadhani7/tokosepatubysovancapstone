<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Lora', serif;
            background: linear-gradient(135deg, #FAFAFA 0%, #F3F4F6 100%);
            color: #1F2937;
            min-height: 100vh;
            padding-top: 5rem;
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
            background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150"%3E%3Cg fill="%23D4AF37" fill-opacity="0.05"%3E%3Ccircle cx="75" cy="75" r="75"/%3E%3C/g%3E%3C/svg%3E');
            z-index: -1;
            animation: particleMove 30s linear infinite;
        }
        @keyframes particleMove {
            0% { background-position: 0 0; }
            100% { background-position: 150px 150px; }
        }
        .hover-scale:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(90deg, #065F46, #10b981);
            color: #FAFAFA;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            border: 1px solid #D4AF37;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #064E3B, #059669);
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
            transition: 0.4s;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .header {
            background: rgba(255, 255, 255, 0.98);
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #D4AF37;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.25);
        }
        .badge {
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .badge-success {
            background: rgba(6, 95, 70, 0.15);
            color: #10b981;
            border: 1px solid rgba(6, 95, 70, 0.3);
        }
        .badge-warning {
            background: rgba(234, 179, 8, 0.15);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 12px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 12px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #B8972F;
        }
        input, select, textarea {
            background: #F9FAFB;
            border: 1px solid #D4AF37;
            color: #1F2937;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
            outline: none;
        }
        li:hover {
            background: rgba(212, 175, 55, 0.1);
            transform: translateX(4px);
        }
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #qr-reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 10px;
            border: 2px solid #D4AF37;
        }
        .modal-overlay {
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
        .popup-enter {
            animation: popupIn 0.4s ease-out;
        }
        @keyframes popupIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .ripple {
            animation: ripple 0.5s linear;
        }
        @keyframes ripple {
            0% { transform: scale(0); opacity: 0.4; }
            100% { transform: scale(3); opacity: 0; }
        }
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
        .glow:hover {
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.5);
        }
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .grid {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            .btn-primary {
                padding: 0.65rem 1.25rem;
                font-size: 0.95rem;
            }
            input, select, textarea {
                padding: 0.65rem;
                font-size: 0.95rem;
            }
            .card {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.75rem;
            }
            h2 {
                font-size: 1.25rem;
            }
            #qr-reader {
                max-width: 100%;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#065F46',
                            secondary: '#10b981',
                            gold: '#D4AF37',
                            dark: {
                                50: '#F9FAFB',
                                100: '#F3F4F6',
                                200: '#E5E7EB',
                                300: '#D1D5DB',
                                400: '#9CA3AF',
                                500: '#6B7280',
                                600: '#4B5563',
                                700: '#374151',
                                800: '#1F2937',
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
    <header class="fixed top-0 w-full header text-brand-dark-800 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-brand-primary rounded-lg p-2 glow hover-scale">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Cinzel'] font-bold text-2xl sm:text-3xl text-brand-dark-800">Sepatu by Sovan</h1>
                    <p class="text-sm text-brand-dark-600">Luxury Footwear Collection</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('transactions.index') }}" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" title="Kembali ke Daftar Transaksi">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" title="Dashboard">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-12 max-w-7xl">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 text-brand-dark-800 p-5 rounded-xl fade-in flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-2 mr-4">
                        <svg class="h-6 w-6 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-base font-['Cinzel']">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-10 card rounded-xl p-6 sm:p-8 fade-in">
            <h1 class="font-['Cinzel'] text-2xl sm:text-3xl font-bold text-brand-dark-800 flex items-center">
                <svg class="h-8 w-8 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Transaksi Baru
            </h1>
            <p class="text-base text-brand-dark-600 mt-2">Pilih unit produk premium dengan pengalaman transaksi yang elegan</p>
        </div>

        <!-- Success/Error Popup Modal -->
        <div x-show="showPopup" class="modal-overlay" x-cloak x-transition:enter="popup-enter" @click.self="closePopup">
            <div class="card rounded-xl p-6 sm:p-8 w-full max-w-md sm:max-w-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-brand-dark-800 font-['Cinzel']" x-text="popupTitle"></h3>
                    <button type="button" @click="closePopup" class="text-brand-dark-600 hover:text-brand-gold p-2 rounded-full hover:bg-brand-dark-100 hover-scale glow">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto mb-4" :class="popupType === 'success' ? 'text-brand-gold' : 'text-red-500'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="popupType === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" />
                    </svg>
                    <p class="text-brand-dark-800 text-base sm:text-lg" x-text="popupMessage"></p>
                    <button type="button" @click="closePopup" class="mt-6 btn-primary px-5 py-2 text-base sm:text-lg hover-scale ripple">
                        OK
                    </button>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                <!-- Product Selection -->
                <div class="card rounded-xl p-6 sm:p-8 fade-in">
                    <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 mb-6 flex items-center font-['Cinzel']">
                        <svg class="h-6 w-6 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-2-4H7a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                        </svg>
                        Pilih Unit Produk
                    </h2>

                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-6">
                        <div class="relative flex-grow w-full">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-brand-dark-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input="searchUnits" placeholder="Cari nama, warna, ukuran, atau kode unit..." class="w-full pl-12 pr-4 py-3 text-base sm:text-lg rounded-lg">
                        </div>
                        <div class="flex space-x-3 w-full sm:w-auto">
                            <button type="button" @click="openScanner" class="btn-primary px-4 py-3 text-base sm:text-lg flex items-center justify-center hover-scale ripple w-full sm:w-auto">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                </svg>
                                Scan QR
                            </button>
                            <button type="button" @click="focusHardwareInput" class="btn-primary px-4 py-3 text-base sm:text-lg flex items-center justify-center hover-scale ripple w-full sm:w-auto">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                </svg>
                                Scan Hardware
                            </button>
                        </div>
                        <input type="text" x-model="qrInput" @input="handleHardwareQrScan" x-ref="qrInput" class="hidden" placeholder="Pindai dengan perangkat keras">
                    </div>

                    <!-- QR Scanner Modal -->
                    <div x-show="isScannerOpen" class="modal-overlay" x-cloak @click.self="closeScanner">
                        <div class="card rounded-xl p-6 sm:p-8 w-full max-w-md">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg sm:text-xl font-semibold text-brand-dark-800 font-['Cinzel']">Scan QR Code Unit</h3>
                                <button type="button" @click="closeScanner" class="text-brand-dark-600 hover:text-brand-gold p-2 rounded-full hover:bg-brand-dark-100 hover-scale glow">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div id="qr-reader"></div>
                            <p class="text-sm text-brand-dark-600 mt-4 text-center">Arahkan kamera ke kode QR unit produk</p>
                            <p class="text-sm text-red-600 mt-3 text-center" x-text="scanError" x-show="scanError"></p>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="searchResults.length > 0" class="card border rounded-xl mb-6 max-h-60 overflow-y-auto custom-scrollbar fade-in" x-cloak>
                        <ul class="divide-y divide-brand-dark-200">
                            <template x-for="unit in searchResults" :key="unit.unit_code">
                                <li class="p-4 transition-colors cursor-pointer hover-scale glow" @click="addToCart(unit)">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-brand-dark-800 text-base sm:text-lg" x-text="unit.product_name"></p>
                                            <div class="flex items-center mt-2 space-x-3">
                                                <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                <span class="text-sm text-brand-dark-600" x-text="unit.color"></span>
                                                <span class="text-brand-dark-400">|</span>
                                                <span class="text-sm text-brand-dark-600" x-text="`Ukuran: ${unit.size}`"></span>
                                                <span class="text-brand-dark-400">|</span>
                                                <span class="text-sm text-brand-dark-600" x-text="`Kode: ${unit.unit_code}`"></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-brand-dark-800 text-base sm:text-lg" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                            <button type="button" class="mt-2 text-sm bg-brand-primary text-brand-gold py-1.5 px-3 rounded-full font-semibold hover-scale ripple">+ Tambah</button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Product Units List -->
                    <div class="border border-brand-dark-200 rounded-xl max-h-96 overflow-y-auto custom-scrollbar">
                        <div x-show="availableUnits.length === 0" class="text-center py-12 text-brand-dark-600">
                            <svg class="h-12 w-12 mx-auto text-brand-gold/50 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-base sm:text-lg font-medium">Tidak ada unit produk tersedia</p>
                            <p class="text-sm text-brand-dark-600">Coba lagi nanti</p>
                        </div>
                        <ul class="divide-y divide-brand-dark-200">
                            <template x-for="unit in availableUnits" :key="unit.unit_code">
                                <li class="p-4 transition-colors cursor-pointer fade-in hover-scale glow" @click="addToCart(unit)">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-brand-dark-800 text-base sm:text-lg" x-text="unit.product_name"></p>
                                            <div class="flex items-center mt-2 space-x-3">
                                                <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(unit.color)}`"></span>
                                                <span class="text-sm text-brand-dark-600" x-text="unit.color"></span>
                                                <span class="text-brand-dark-400">|</span>
                                                <span class="text-sm text-brand-dark-600" x-text="`Ukuran: ${unit.size}`"></span>
                                                <span class="text-brand-dark-400">|</span>
                                                <span class="text-sm text-brand-dark-600" x-text="`Kode: ${unit.unit_code}`"></span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-brand-dark-800 text-base sm:text-lg" x-text="unit.discount_price ? formatRupiah(unit.discount_price) : formatRupiah(unit.selling_price)"></p>
                                            <button type="button" class="mt-2 text-sm bg-brand-primary text-brand-gold py-1.5 px-3 rounded-full font-semibold hover-scale ripple">+ Tambah</button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Customer & Cart -->
                <div class="card rounded-xl p-6 sm:p-8 fade-in">
                    <!-- Customer Info -->
                    <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 mb-6 flex items-center font-['Cinzel']">
                        <svg class="h-6 w-6 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Pelanggan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm sm:text-base font-medium text-brand-dark-600 mb-2">Nama Pelanggan</label>
                            <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" value="{{ old('customer_name') }}" class="w-full px-4 py-3 text-base sm:text-lg rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-medium text-brand-dark-600 mb-2">No. Telepon</label>
                            <input type="text" name="customer_phone" placeholder="Masukkan nomor telepon" value="{{ old('customer_phone') }}" class="w-full px-4 py-3 text-base sm:text-lg rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-medium text-brand-dark-600 mb-2">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <select name="payment_method" id="payment_method" x-model="paymentMethod" @change="updateCardTypeVisibility" class="w-full px-4 py-3 text-base sm:text-lg rounded-lg" required>
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Pilih metode pembayaran</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="debit" {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            </select>
                        </div>
                        <div x-show="paymentMethod === 'debit'" x-cloak>
                            <label class="block text-sm sm:text-base font-medium text-brand-dark-600 mb-2">Tipe Kartu <span class="text-red-600">*</span></label>
                            <select name="card_type" id="card_type" class="w-full px-4 py-3 text-base sm:text-lg rounded-lg" x-model="cardType">
                                <option value="" disabled {{ old('card_type') ? '' : 'selected' }}>Pilih tipe kartu</option>
                                <option value="Mandiri" {{ old('card_type') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BRI" {{ old('card_type') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BCA" {{ old('card_type') == 'BCA' ? 'selected' : '' }}>BCA</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm sm:text-base font-medium text-brand-dark-600 mb-2">Catatan</label>
                            <textarea name="notes" rows="4" placeholder="Catatan tambahan untuk transaksi" class="w-full px-4 py-3 text-base sm:text-lg rounded-lg">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Cart -->
                    <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 mb-6 flex items-center border-t border-brand-dark-200 pt-6 font-['Cinzel']">
                        <svg class="h-6 w-6 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Keranjang Belanja
                    </h2>

                    <div x-show="cart.length === 0" class="text-center py-12 text-brand-dark-600 border border-brand-dark-200 rounded-xl bg-brand-dark-50/10 mb-6">
                        <svg class="h-16 w-16 mx-auto text-brand-gold/50 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-base sm:text-lg font-medium">Keranjang Kosong</p>
                        <p class="text-sm text-brand-dark-600">Tambahkan unit produk dari daftar di samping</p>
                    </div>

                    <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto custom-scrollbar mb-6 space-y-4">
                        <ul class="space-y-3">
                            <template x-for="(item, index) in cart" :key="index">
                                <li class="border border-brand-dark-200 rounded-xl p-4 bg-brand-dark-50/10 relative fade-in hover-scale glow">
                                    <button type="button" @click="removeItem(index)" class="absolute top-3 right-3 text-brand-dark-600 hover:text-red-600 hover-scale">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <p class="font-semibold text-brand-dark-800 pr-8 text-base sm:text-lg" x-text="item.name"></p>
                                    <div class="flex items-center mt-2 space-x-3">
                                        <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                        <span class="text-sm text-brand-dark-600" x-text="item.color"></span>
                                        <span class="text-brand-dark-400">|</span>
                                        <span class="text-sm text-brand-dark-600" x-text="`Ukuran: ${item.size}`"></span>
                                        <span class="text-brand-dark-400">|</span>
                                        <span class="text-sm text-brand-dark-600" x-text="`Kode: ${item.unit_code}`"></span>
                                    </div>
                                    <div class="flex justify-between items-center mt-4">
                                        <p class="font-semibold text-brand-dark-800 text-base sm:text-lg" x-text="item.discount_price ? formatRupiah(item.discount_price) : formatRupiah(item.selling_price)"></p>
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
                    <div class="border-t border-brand-dark-200 pt-6">
                        <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 mb-4 font-['Cinzel']">Ringkasan Pembayaran</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between text-base sm:text-lg">
                                <span class="text-brand-dark-600">Subtotal</span>
                                <span class="font-semibold text-brand-dark-800" x-text="formatRupiah(calculateSubtotal())"></span>
                            </div>
                            <div class="flex justify-between text-base sm:text-lg">
                                <span class="text-brand-gold">Diskon</span>
                                <span class="font-semibold text-brand-gold" x-text="formatRupiah(calculateDiscount())"></span>
                            </div>
                            <div class="flex justify-between items-center text-base sm:text-lg">
                                <span class="text-brand-dark-600">Harga Baru</span>
                                <div class="flex items-center">
                                    <span class="text-brand-dark-600 mr-3">Rp</span>
                                    <input type="number" name="discount_amount" x-model.number="new_total" min="0" :max="calculateSubtotal()" placeholder="Masukkan harga baru" class="w-28 text-right px-3 py-2 text-base sm:text-lg rounded-lg" @input="updateNewTotal">
                                </div>
                            </div>
                            <div class="flex justify-between border-t border-brand-dark-200 pt-4 text-base sm:text-lg">
                                <span class="font-bold text-brand-gold">Total Bayar</span>
                                <span class="font-bold text-lg sm:text-xl text-brand-gold" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 py-3 btn-primary text-base sm:text-lg flex items-center justify-center hover-scale ripple" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
                            <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                paymentMethod: '',
                cardType: '',
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
                        'Merah': '#DC2626',
                        'Hitam': '#1F2937',
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

                updateCardTypeVisibility() {
                    console.log('Payment method changed to:', this.paymentMethod);
                    if (this.paymentMethod !== 'debit') {
                        this.cardType = '';
                    }
                },

                validateForm(event) {
                    console.log('Validating form:', {
                        cart: this.cart,
                        paymentMethod: this.paymentMethod,
                        cardType: this.cardType,
                        new_total: this.new_total
                    });

                    if (this.cart.length === 0) {
                        event.preventDefault();
                        this.popupTitle = 'Keranjang Kosong';
                        this.popupMessage = 'Tambahkan unit produk terlebih dahulu.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.log('Validation failed: Cart is empty');
                        return false;
                    }

                    if (!this.paymentMethod) {
                        event.preventDefault();
                        this.popupTitle = 'Metode Pembayaran Kosong';
                        this.popupMessage = 'Silakan pilih metode pembayaran!';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.log('Validation failed: Payment method is empty');
                        return false;
                    }

                    if (this.paymentMethod === 'debit' && !this.cardType) {
                        event.preventDefault();
                        this.popupTitle = 'Tipe Kartu Kosong';
                        this.popupMessage = 'Silakan pilih tipe kartu untuk metode pembayaran Debit!';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.log('Validation failed: Card type is empty for debit');
                        return false;
                    }

                    const newTotal = parseFloat(this.new_total);
                    if (this.new_total === '' || isNaN(newTotal) || newTotal < 0) {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru harus diisi dan tidak boleh kurang dari 0.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.log('Validation failed: Invalid new total');
                        return false;
                    }

                    if (newTotal > this.calculateSubtotal()) {
                        event.preventDefault();
                        this.popupTitle = 'Harga Baru Tidak Valid';
                        this.popupMessage = 'Harga baru tidak boleh melebihi subtotal.';
                        this.popupType = 'error';
                        this.showPopup = true;
                        console.log('Validation failed: New total exceeds subtotal');
                        return false;
                    }

                    console.log('Form validation passed, submitting form');
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
                    this.new_total = '{{ old('discount_amount') }}';
                    this.paymentMethod = '{{ old('payment_method') }}';
                    this.cardType = '{{ old('card_type') }}';
                    this.$refs.qrInput.focus();
                    console.log('Initialized with:', {
                        cart: this.cart,
                        paymentMethod: this.paymentMethod,
                        cardType: this.cardType,
                        new_total: this.new_total
                    });
                }
            };
        }
    </script>
</body>
</html>