<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }

        /* Dynamic animated background */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            background-size: 400%;
            animation: gradientShift 15s ease infinite;
            padding-top: 7rem;
            color: #E0E0E0;
            min-height: 100vh;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Smooth pulse animation for interactive elements */
        .pulse-hover:hover {
            animation: pulse 0.6s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Neon glow effect for buttons */
        .btn-neon {
            background: linear-gradient(90deg, #ff00cc, #3333ff);
            border: none;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(255, 0, 204, 0.5), 0 0 30px rgba(51, 51, 255, 0.3);
            color: #fff;
        }
        .btn-neon:hover {
            box-shadow: 0 0 25px rgba(255, 0, 204, 0.8), 0 0 50px rgba(51, 51, 255, 0.5);
            transform: translateY(-2px);
        }
        .btn-neon::before {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(90deg, #ff00cc, #3333ff);
            filter: blur(10px);
            opacity: 0.4;
            z-index: -1;
        }

        /* Enhanced header with neon border */
        .bg-neon-header {
            background: rgba(10, 10, 20, 0.95);
            border-bottom: 2px solid #ff00cc;
            box-shadow: 0 0 20px rgba(255, 0, 204, 0.3);
        }

        /* Advanced glassmorphism with neon edges */
        .card-neon {
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 0, 204, 0.2);
            box-shadow: 0 0 20px rgba(255, 0, 204, 0.2), 0 0 40px rgba(51, 51, 255, 0.1);
            transition: all 0.4s ease;
        }
        .card-neon:hover {
            box-shadow: 0 0 30px rgba(255, 0, 204, 0.4), 0 0 60px rgba(51, 51, 255, 0.2);
            transform: translateY(-3px);
        }

        /* Neon status badges */
        .status-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            border: 1px solid rgba(255, 0, 204, 0.2);
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 0, 204, 0.2);
        }

        /* Futuristic scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 0, 204, 0.1);
            border-radius: 12px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ff00cc, #3333ff);
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(255, 0, 204, 0.5);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ff33cc, #6666ff);
        }

        /* Input and select with neon focus */
        input, select, textarea {
            background: rgba(20, 20, 40, 0.8);
            border: 1px solid rgba(255, 0, 204, 0.3);
            color: #E0E0E0;
            transition: all 0.3s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #ff00cc;
            box-shadow: 0 0 12px rgba(255, 0, 204, 0.5);
        }

        /* Slide-in animation */
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* QR reader styling */
        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 1rem;
        }

        /* Modal overlay */
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
            z-index: 50;
        }

        /* Print styles */
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
                                pink: '#ff00cc',
                                blue: '#3333ff',
                            },
                            dark: {
                                50: '#F9FAFB',
                                100: '#E5E5E5',
                                200: '#D4D4D4',
                                300: '#A3A3A3',
                                400: '#737373',
                                500: '#525252',
                                600: '#404040',
                                700: '#2D2D2D',
                                800: '#1E1E1E',
                                900: '#121212',
                            },
                        }
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(255, 0, 204, 0.3), 0 0 40px rgba(51, 51, 255, 0.2)',
                    },
                    animation: {
                        'gradient-shift': 'gradientShift 15s ease infinite',
                    },
                    keyframes: {
                        gradientShift: {
                            '0%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                            '100%': { backgroundPosition: '0% 50%' },
                        },
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionApp()" x-init="initialize()">
    <div class="flex flex-col">
        <!-- Header -->
        <header class="fixed top-0 w-full bg-neon-header text-white shadow-lg z-50">
            <div class="container mx-auto px-6 py-5 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="bg-brand-neon-pink rounded-full p-3 shadow-neon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-['Playfair_Display'] font-bold text-3xl tracking-tight text-brand-neon-pink">Sepatu by Sovan</h1>
                        <p class="text-sm text-gray-400 font-light">Luxury Footwear Collection</p>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn-neon text-white px-6 py-3 rounded-2xl font-semibold shadow-neon hover:shadow-neon pulse-hover transition-all flex items-center space-x-2">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-12 max-w-7xl">
            <div class="mb-10 card-neon rounded-2xl shadow-neon p-8 border border-brand-neon-pink/20 slide-in">
                <h1 class="font-['Playfair_Display'] text-5xl font-bold text-white mb-3 flex items-center">
                    <svg class="h-12 w-12 mr-4 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Transaksi Baru
                </h1>
                <p class="text-gray-400 text-lg">Tambahkan produk dan lengkapi detail transaksi dengan gaya dan kemudahan</p>
            </div>

            <!-- Form -->
            <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Product Selection -->
                    <div class="card-neon rounded-2xl shadow-neon p-8 border border-brand-neon-pink/20 slide-in">
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                            <svg class="h-6 w-6 mr-3 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0l-2-4H7a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                            </svg>
                            Pilih Produk
                        </h2>

                        <div class="flex items-center space-x-4 mb-6">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" x-model="searchQuery" @input="searchProducts" placeholder="Cari nama, warna, atau ukuran produk..." class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all">
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" @click="openScanner" class="btn-neon text-white px-4 py-3 rounded-2xl flex items-center text-sm font-semibold shadow-neon pulse-hover">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                    </svg>
                                    Scan QR
                                </button>
                                <button type="button" @click="focusHardwareInput" class="btn-neon text-white px-4 py-3 rounded-2xl flex items-center text-sm font-semibold shadow-neon pulse-hover">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8h4" />
                                    </svg>
                                    Scan Hardware
                                </button>
                            </div>
                            <input type="text" x-model="qrInput" @change="handleHardwareQrScan" x-ref="qrInput" class="hidden" placeholder="Pindai dengan perangkat keras">
                        </div>

                        <!-- QR Scanner Modal -->
                        <div x-show="isScannerOpen" class="modal-overlay" x-cloak @click.self="closeScanner">
                            <div class="card-neon rounded-2xl p-8 w-full max-w-lg border border-brand-neon-pink/20">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-xl font-semibold text-white font-['Playfair_Display']">Scan QR Code</h3>
                                    <button type="button" @click="closeScanner" class="text-gray-400 hover:text-brand-neon-pink p-2 rounded-full hover:bg-brand-dark-700 transition-colors">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div id="qr-reader"></div>
                                <p class="text-sm text-gray-400 mt-4 text-center">Arahkan kamera ke kode QR produk</p>
                                <p class="text-sm text-red-300 mt-2 text-center" x-text="scanError" x-show="scanError"></p>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div x-show="searchResults.length > 0" class="card-neon border border-brand-neon-pink/20 rounded-2xl shadow-sm mb-6 max-h-48 overflow-y-auto custom-scrollbar slide-in" x-cloak>
                            <ul class="divide-y divide-brand-neon-pink/20">
                                <template x-for="product in searchResults" :key="product.id">
                                    <li class="p-4 hover:bg-brand-dark-700/60 transition-colors cursor-pointer" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-white text-sm" x-text="product.name"></p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-xs text-gray-400" x-text="product.color"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs" :class="product.stock > 5 ? 'text-green-300' : 'text-orange-300'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-white text-sm" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-brand-neon-pink text-white py-1 px-3 rounded-full transition-colors font-semibold">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Product List -->
                        <div class="border border-brand-neon-pink/20 rounded-2xl max-h-80 overflow-y-auto custom-scrollbar">
                            <div x-show="availableProducts.length === 0" class="text-center py-12 text-gray-400">
                                <svg class="h-12 w-12 mx-auto text-brand-neon-pink/50 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-base">Tidak ada produk tersedia</p>
                                <p class="text-sm text-gray-500">Coba lagi nanti</p>
                            </div>
                            <ul class="divide-y divide-brand-neon-pink/20">
                                <template x-for="product in availableProducts" :key="product.id">
                                    <li class="p-4 hover:bg-brand-dark-700/60 transition-colors cursor-pointer slide-in" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-white text-sm" x-text="product.name"></p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-xs text-gray-400" x-text="product.color"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs text-gray-400" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="text-gray-500">|</span>
                                                    <span class="text-xs" :class="product.stock > 5 ? 'text-green-300' : 'text-orange-300'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-white text-sm" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-brand-neon-pink text-white py-1 px-3 rounded-full transition-colors font-semibold">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Customer & Cart -->
                    <div class="card-neon rounded-2xl shadow-neon p-8 border border-brand-neon-pink/20 slide-in">
                        <!-- Customer Info -->
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                            <svg class="h-6 w-6 mr-3 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Masukkan nomor telepon" class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                <input type="email" name="customer_email" placeholder="email@example.com" class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Metode Pembayaran <span class="text-red-300">*</span></label>
                                <select name="payment_method" id="payment_method" class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all" required>
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                                <textarea name="notes" rows="3" placeholder="Catatan tambahan untuk transaksi" class="w-full border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all"></textarea>
                            </div>
                        </div>

                        <!-- Cart -->
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center border-t border-brand-neon-pink/20 pt-6">
                            <svg class="h-6 w-6 mr-3 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Keranjang Belanja
                        </h2>

                        <div x-show="cart.length === 0" class="text-center py-12 text-gray-400 border border-brand-neon-pink/20 rounded-2xl bg-brand-dark-800/60 mb-6">
                            <svg class="h-16 w-16 mx-auto text-brand-neon-pink/50 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-base font-medium">Keranjang Kosong</p>
                            <p class="text-sm text-gray-500">Tambahkan produk dari daftar di samping</p>
                        </div>

                        <div x-show="cart.length > 0" class="max-h-56 overflow-y-auto custom-scrollbar mb-6 space-y-4">
                            <ul class="space-y-3">
                                <template x-for="(item, index) in cart" :key="index">
                                    <li class="border border-brand-neon-pink/20 rounded-2xl p-4 bg-brand-dark-800/60 relative slide-in">
                                        <button type="button" @click="removeItem(index)" class="absolute top-3 right-3 text-gray-400 hover:text-red-300 transition-colors">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <p class="font-medium text-white pr-8 text-sm" x-text="item.name"></p>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span class="inline-block h-2 w-2 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                            <span class="text-xs text-gray-400" x-text="item.color"></span>
                                            <span class="text-gray-500">|</span>
                                            <span class="text-xs text-gray-400" x-text="`Ukuran: ${item.size}`"></span>
                                        </div>
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="flex items-center border border-brand-neon-pink/20 rounded-lg overflow-hidden">
                                                <button type="button" @click="decrementQuantity(index)" class="bg-brand-dark-700 hover:bg-brand-dark-600 px-3 py-2 transition-colors">
                                                    <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" x-model.number="item.quantity" min="1" :max="item.stock" class="w-12 text-center bg-brand-dark-800 text-white py-2 text-sm focus:ring-2 focus:ring-brand-neon-pink" @change="updateQuantity(index)">
                                                <button type="button" @click="incrementQuantity(index)" class="bg-brand-dark-700 hover:bg-brand-dark-600 px-3 py-2 transition-colors">
                                                    <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="font-medium text-white text-sm" x-text="formatRupiah(item.selling_price * item.quantity)"></p>
                                        </div>
                                        <input type="hidden" :name="'products['+index+'][id]'" x-model="item.id">
                                        <input type="hidden" :name="'products['+index+'][quantity]'" x-model="item.quantity">
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-brand-neon-pink/20 pt-6">
                            <h2 class="text-xl font-semibold text-white mb-4 font-['Playfair_Display']">Ringkasan Pembayaran</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between text-base">
                                    <span class="text-gray-400">Subtotal</span>
                                    <span class="font-medium text-white" x-text="formatRupiah(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between items-center text-base">
                                    <span class="text-gray-400">Diskon</span>
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-3">Rp</span>
                                        <input type="number" name="discount_amount" x-model="discount" min="0" class="w-24 text-right border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-2xl px-3 py-2 text-sm focus:ring-2 focus:ring-brand-neon-pink transition-all">
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-brand-neon-pink/20 pt-4 text-base">
                                    <span class="font-bold text-white">Total Bayar</span>
                                    <span class="font-bold text-lg text-brand-neon-pink" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 py-3 btn-neon text-white rounded-2xl font-semibold transition-all flex items-center justify-center text-sm shadow-neon pulse-hover" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
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
    </div>

    <script>
        function transactionApp() {
            return {
                availableProducts: @json($products),
                searchQuery: '',
                searchResults: [],
                cart: [],
                discount: 0,
                isScannerOpen: false,
                qrScanner: null,
                scanError: '',
                scannedProductIds: [],
                qrInput: '',

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

                searchProducts() {
                    this.searchResults = this.searchQuery.trim() ?
                        this.availableProducts.filter(p =>
                            p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            p.color.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            p.size.toLowerCase().includes(this.searchQuery.toLowerCase())
                        ) : [];
                },

                addToCart(product) {
                    const index = this.cart.findIndex(item => item.id === product.id);
                    if (index >= 0) {
                        if (this.cart[index].quantity < product.stock) {
                            this.cart[index].quantity++;
                        } else {
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            color: product.color,
                            size: product.size,
                            selling_price: product.selling_price,
                            quantity: 1,
                            stock: product.stock
                        });
                        this.scannedProductIds.push(product.id);
                    }
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.$forceUpdate();
                },

                removeItem(index) {
                    const removedProductId = this.cart[index].id;
                    this.scannedProductIds = this.scannedProductIds.filter(id => id !== removedProductId);
                    this.cart.splice(index, 1);
                    this.$forceUpdate();
                },

                incrementQuantity(index) {
                    if (this.cart[index].quantity < this.cart[index].stock) {
                        this.cart[index].quantity++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                    this.$forceUpdate();
                },

                decrementQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    }
                    this.$forceUpdate();
                },

                updateQuantity(index) {
                    let qty = this.cart[index].quantity;
                    if (qty < 1) {
                        this.cart[index].quantity = 1;
                    } else if (qty > this.cart[index].stock) {
                        this.cart[index].quantity = this.cart[index].stock;
                        alert('Kuantitas disesuaikan dengan stok tersedia');
                    }
                    this.$forceUpdate();
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => total + (item.selling_price * item.quantity), 0);
                },

                calculateTotal() {
                    return Math.max(0, this.calculateSubtotal() - this.discount);
                },

                validateForm(event) {
                    if (this.cart.length === 0) {
                        event.preventDefault();
                        alert('Keranjang masih kosong! Tambahkan produk terlebih dahulu.');
                        return false;
                    }
                    if (!document.getElementById('payment_method').value) {
                        event.preventDefault();
                        alert('Silakan pilih metode pembayaran!');
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

                focusHardwareInput() {
                    this.closeScanner();
                    this.scanError = '';
                    this.qrInput = '';
                    this.$refs.qrInput.focus();
                },

                handleQrScan(decodedText) {
                    this.scanError = '';
                    let productId;
                    try {
                        const url = new URL(decodedText);
                        const pathSegments = url.pathname.split('/');
                        productId = pathSegments[pathSegments.length - 1];
                        if (!productId || isNaN(productId)) {
                            throw new Error('Invalid product ID');
                        }
                    } catch (e) {
                        this.scanError = 'QR code tidak valid. Harus berisi URL produk (contoh: http://example.com/inventory/123).';
                        return;
                    }

                    const product = this.availableProducts.find(p => p.id == productId);
                    if (!product) {
                        this.scanError = `Produk dengan ID ${productId} tidak ditemukan.`;
                        return;
                    }

                    if (this.scannedProductIds.includes(product.id)) {
                        this.scanError = `Produk "${product.name}" sudah discan sebelumnya.`;
                        return;
                    }

                    if (product.stock > 0) {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            color: product.color,
                            size: product.size,
                            selling_price: product.selling_price,
                            quantity: 1,
                            stock: product.stock
                        });
                        this.scannedProductIds.push(product.id);
                        alert(`Produk ${product.name} berhasil ditambahkan ke keranjang!`);
                        this.closeScanner();
                    } else {
                        this.scanError = `Stok produk "${product.name}" habis.`;
                    }
                },

                handleHardwareQrScan() {
                    if (!this.qrInput) return;
                    this.scanError = '';
                    let productId;
                    try {
                        const url = new URL(this.qrInput);
                        const pathSegments = url.pathname.split('/');
                        productId = pathSegments[pathSegments.length - 1];
                        if (!productId || isNaN(productId)) {
                            throw new Error('Invalid product ID');
                        }
                    } catch (e) {
                        this.scanError = 'QR code tidak valid. Harus berisi URL produk (contoh: http://example.com/inventory/123).';
                        this.qrInput = '';
                        return;
                    }

                    const product = this.availableProducts.find(p => p.id == productId);
                    if (!product) {
                        this.scanError = `Produk dengan ID ${productId} tidak ditemukan.`;
                        this.qrInput = '';
                        return;
                    }

                    if (this.scannedProductIds.includes(product.id)) {
                        this.scanError = `Produk "${product.name}" sudah discan sebelumnya.`;
                        this.qrInput = '';
                        return;
                    }

                    if (product.stock > 0) {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            color: product.color,
                            size: product.size,
                            selling_price: product.selling_price,
                            quantity: 1,
                            stock: product.stock
                        });
                        this.scannedProductIds.push(product.id);
                        alert(`Produk ${product.name} berhasil ditambahkan ke keranjang!`);
                        this.qrInput = '';
                    } else {
                        this.scanError = `Stok produk "${product.name}" habis.`;
                        this.qrInput = '';
                    }
                },

                initialize() {
                    // Initialization logic
                }
            };
        }
    </script>
</body>
</html>