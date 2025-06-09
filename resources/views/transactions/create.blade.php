<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        button:focus {
            outline: 2px solid #1E1E1E;
            outline-offset: 2px;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: '#F5F5F5',
                            100: '#EBEBEB',
                            200: '#D6D6D6',
                            300: '#C2C2C2',
                            400: '#9E9E9E',
                            500: '#757575',
                            600: '#5E5E5E',
                            700: '#1E1E1E',
                            800: '#141414',
                            900: '#0A0A0A',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans" x-data="transactionApp()" x-init="initialize()">
    <div class="min-h-screen flex flex-col">
        <!-- Header/Navigation -->
        <header class="bg-dark-700 text-white shadow-md">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <div>
                        <h1 class="font-bold text-xl">Sepatu by Sovan</h1>
                        <p class="text-xs text-gray-400">Premium Footwear Collection</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ url('/transactions') }}" class="text-sm bg-black hover:bg-dark-600 px-3 py-2 rounded-lg transition-all flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-6">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Buat Transaksi Baru</h1>
                <p class="text-gray-600 mt-1">Tambahkan produk ke keranjang dan selesaikan transaksi</p>
            </div>

            <!-- Form -->
            <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilih Produk -->
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-dark-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Daftar Produk
                        </h2>
                        
                        <div class="mb-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input="searchProducts" placeholder="Cari produk, warna, atau ukuran..." class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring focus:border-dark-500">
                        </div>
                        
                        <!-- Scan QR -->
                        <div class="flex gap-2 mb-4">
                            <input type="text" x-model="qrCode" @keydown.enter.prevent="scanQR" placeholder="Masukkan kode QR" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500">
                            <button type="button" @click="scanQR" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- QR Scanner Button -->
                        <button type="button" @click="openQRScanner" class="w-full mb-4 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors flex items-center justify-center z-10" :disabled="isButtonDisabled">
                            <svg class="h-5 w-5 mr-2 text-dark-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buka Kamera QR Scanner
                        </button>
                        
                        <!-- Hasil Pencarian -->
                        <div x-show="searchResults.length > 0" class="bg-white border rounded-lg shadow-sm mb-4 max-h-48 overflow-y-auto" x-cloak>
                            <ul class="divide-y divide-gray-100">
                                <template x-for="product in searchResults" :key="product.id">
                                    <li class="p-3 hover:bg-gray-50 transition-colors cursor-pointer" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-gray-800" x-text="product.name"></p>
                                                <div class="flex items-center mt-1">
                                                    <span class="inline-block h-3 w-3 rounded-full mr-1" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-xs text-gray-500" x-text="product.color"></span>
                                                    <span class="mx-1.5 text-gray-300">|</span>
                                                    <span class="text-xs text-gray-500" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="mx-1.5 text-gray-300">|</span>
                                                    <span class="text-xs" :class="product.stock > 5 ? 'text-green-600' : 'text-orange-600'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-gray-100 hover:bg-gray-200 text-dark-700 py-1 px-2 rounded-full transition-colors">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        
                        <!-- Daftar Produk -->
                        <div class="border rounded-lg max-h-96 overflow-y-auto">
                            <div x-show="availableProducts.length === 0" class="text-center py-10 text-gray-500">
                                <svg class="h-10 w-10 mx-auto text-gray-300 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p>Tidak ada produk tersedia</p>
                            </div>
                            <ul class="divide-y divide-gray-100">
                                <template x-for="product in availableProducts" :key="product.id">
                                    <li class="p-3 hover:bg-gray-50 transition-colors cursor-pointer" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-medium text-gray-800" x-text="product.name"></p>
                                                <div class="flex items-center mt-1">
                                                    <span class="inline-block h-3 w-3 rounded-full mr-1" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-xs text-gray-500" x-text="product.color"></span>
                                                    <span class="mx-1.5 text-gray-300">|</span>
                                                    <span class="text-xs text-gray-500" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="mx-1.5 text-gray-300">|</span>
                                                    <span class="text-xs" :class="product.stock > 5 ? 'text-green-600' : 'text-orange-600'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-gray-800" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-1 text-xs bg-gray-100 hover:bg-gray-200 text-dark-700 py-1 px-2 rounded-full transition-colors">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Informasi Pelanggan & Keranjang -->
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <!-- Informasi Pelanggan -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-dark-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Contoh: 081234567890" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="customer_email" placeholder="email@example.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500" required>
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="notes" rows="2" placeholder="Catatan tambahan untuk transaksi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:border-dark-500"></textarea>
                            </div>
                        </div>
                        
                        <!-- Keranjang Belanja -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-t pt-4">
                            <svg class="h-5 w-5 mr-2 text-dark-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Keranjang Belanja
                        </h2>
                        
                        <div x-show="cart.length === 0" class="text-center py-8 text-gray-500 border rounded-lg bg-gray-50 mb-4">
                            <svg class="h-12 w-12 mx-auto text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="mb-1">Keranjang belanja kosong</p>
                            <p class="text-xs text-gray-400">Tambahkan produk dari daftar atau scan QR</p>
                        </div>
                        
                        <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto mb-4">
                            <ul class="space-y-3">
                                <template x-for="(item, index) in cart" :key="index">
                                    <li class="border rounded-lg p-3 bg-gray-50 relative hover:shadow-sm transition-shadow">
                                        <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <p class="font-medium text-gray-800 pr-6" x-text="item.name"></p>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-block h-3 w-3 rounded-full mr-1" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                            <span class="text-xs text-gray-500" x-text="item.color"></span>
                                            <span class="mx-1.5 text-gray-300">|</span>
                                            <span class="text-xs text-gray-500" x-text="`Ukuran: ${item.size}`"></span>
                                        </div>
                                        <div class="flex justify-between items-center mt-3">
                                            <div class="flex items-center border rounded overflow-hidden">
                                                <button type="button" @click="decrementQuantity(index)" class="bg-gray-100 hover:bg-gray-200 px-2 py-1 transition-colors">
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" x-model.number="item.quantity" min="1" :max="item.stock" class="w-10 text-center border-x py-1 text-sm" @change="updateQuantity(index)">
                                                <button type="button" @click="incrementQuantity(index)" class="bg-gray-100 hover:bg-gray-200 px-2 py-1 transition-colors">
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="font-bold text-gray-800" x-text="formatRupiah(item.selling_price * item.quantity)"></p>
                                        </div>
                                        <input type="hidden" :name="'products['+index+'][id]'" x-model="item.id">
                                        <input type="hidden" :name="'products['+index+'][quantity]'" x-model="item.quantity">
                                    </li>
                                </template>
                            </ul>
                        </div>
                        
                        <!-- Ringkasan -->
                        <div class="border-t pt-4 mt-2">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Ringkasan Pembayaran</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium" x-text="formatRupiah(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Diskon</span>
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Rp</span>
                                        <input type="number" name="discount_amount" x-model="discount" min="0" class="w-24 text-right border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring focus:border-dark-500">
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-3 mt-3">
                                    <span class="font-bold text-gray-800">Total Bayar</span>
                                    <span class="font-bold text-lg text-dark-700" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-5 py-3 bg-dark-700 hover:bg-dark-600 text-white rounded-lg font-medium transition-colors flex items-center justify-center" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
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
    </div>

    <!-- QR Scanner Modal -->
    <div x-show="showQRScanner" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-lg p-5 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">QR Code Scanner</h3>
                <button @click="closeQRScanner" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="bg-gray-100 rounded-lg overflow-hidden relative" style="height: 300px;">
                <video id="qr-video" class="w-full h-full object-cover" autoplay playsinline></video>
                <canvas id="qr-canvas" class="hidden"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-2/3 h-2/3 border-2 border-dark-500 rounded-lg opacity-70"></div>
                </div>
            </div>
            
            <div class="mt-4 text-center text-sm text-gray-600">
                <p x-show="!scanActive && !cameraError">Menyiapkan kamera...</p>
                <p x-show="cameraError" class="text-red-600" x-text="cameraErrorMessage"></p>
                <p x-show="scanActive">Arahkan kamera ke QR Code produk...</p>
                <p x-show="lastScanned" class="text-green-600 font-medium mt-2">
                    Berhasil memindai produk!
                </p>
            </div>
            
            <div class="mt-4 flex justify-center gap-2">
                <button type="button" @click="startScanning" x-show="cameraError" class="px-4 py-2 bg-dark-700 text-white rounded-lg hover:bg-dark-600 transition-colors">
                    Coba Lagi
                </button>
                <button type="button" @click="closeQRScanner" x-show="scanActive" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Tutup Scanner
                </button>
            </div>
        </div>
    </div>

   <script>
    function transactionApp() {
        return {
            availableProducts: @json($products),
            searchQuery: '',
            searchResults: [],
            cart: [],
            qrCode: '',
            discount: 0,
            showQRScanner: false,
            scanActive: false,
            cameraError: false,
            cameraErrorMessage: '',
            video: null,
            canvas: null,
            ctx: null,
            lastScanned: null,
            isButtonDisabled: false,

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
                console.log('Adding to cart:', product);
                const index = this.cart.findIndex(item => item.id === product.id);
                if (index >= 0) {
                    if (this.cart[index].quantity < product.stock) {
                        this.cart[index].quantity++;
                        console.log('Incremented quantity for:', product.name);
                    } else {
                        alert('Stok tidak mencukupi!');
                        console.warn('Stock insufficient for:', product.name);
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
                    console.log('Added new product to cart:', product.name);
                }
                this.searchQuery = '';
                this.searchResults = [];
                this.$forceUpdate();
            },

            removeItem(index) {
                console.log('Removing item at index:', index);
                this.cart.splice(index, 1);
                this.$forceUpdate();
            },

            incrementQuantity(index) {
                if (this.cart[index].quantity < this.cart[index].stock) {
                    this.cart[index].quantity++;
                    console.log('Incremented quantity at index:', index);
                } else {
                    alert('Stok tidak mencukupi!');
                    console.warn('Stock insufficient at index:', index);
                }
                this.$forceUpdate();
            },

            decrementQuantity(index) {
                if (this.cart[index].quantity > 1) {
                    this.cart[index].quantity--;
                    console.log('Decremented quantity at index:', index);
                }
                this.$forceUpdate();
            },

            updateQuantity(index) {
                let qty = this.cart[index].quantity;
                if (qty < 1) {
                    this.cart[index].quantity = 1;
                    console.log('Adjusted quantity to minimum at index:', index);
                }
                if (qty > this.cart[index].stock) {
                    this.cart[index].quantity = this.cart[index].stock;
                    alert('Kuantitas disesuaikan dengan stok tersedia');
                    console.warn('Adjusted quantity to stock limit at index:', index);
                }
                this.$forceUpdate();
            },

            calculateSubtotal() {
                return this.cart.reduce((total, item) => total + (item.selling_price * item.quantity), 0);
            },

            calculateTotal() {
                return Math.max(0, this.calculateSubtotal() - this.discount);
            },

            async scanQR() {
                if (!this.qrCode) {
                    console.warn('QR code input is empty');
                    alert('Masukkan kode QR terlebih dahulu!');
                    return;
                }
                console.log('Scanning QR code:', this.qrCode);
                const match = this.qrCode.match(/\/inventory\/(\d+)/);
                if (match && match[1]) {
                    const productId = match[1];
                    console.log('Extracted product ID:', productId);
                    try {
                        const response = await fetch(`/inventory/${productId}/json`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        console.log('Fetch response status:', response.status);
                        if (response.ok) {
                            const product = await response.json();
                            console.log('Fetched product data:', product);
                            if (product && product.stock > 0) {
                                this.addToCart({
                                    id: product.id,
                                    name: product.name,
                                    color: product.color,
                                    size: product.size,
                                    selling_price: product.selling_price,
                                    stock: product.stock
                                });
                                this.qrCode = '';
                                console.log('Product added to cart from manual QR scan');
                            } else {
                                alert('Produk tidak ditemukan atau stok habis!');
                                console.warn('Product not found or out of stock:', product);
                            }
                        } else {
                            alert('Gagal mengambil data produk! Status: ' + response.status);
                            console.error('Fetch failed with status:', response.status);
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan saat mengambil data produk!');
                        console.error('Fetch error:', error);
                    }
                } else {
                    alert('Format QR tidak valid!');
                    console.warn('Invalid QR code format:', this.qrCode);
                }
            },

            validateForm(event) {
                if (this.cart.length === 0) {
                    event.preventDefault();
                    alert('Keranjang masih kosong! Tambahkan produk terlebih dahulu.');
                    console.warn('Form submission blocked: cart is empty');
                    return false;
                }
                if (!document.getElementById('payment_method').value) {
                    event.preventDefault();
                    alert('Silakan pilih metode pembayaran!');
                    console.warn('Form submission blocked: payment method not selected');
                    return false;
                }
                console.log('Form validated successfully');
                return true;
            },

            initialize() {
                console.log('Initializing transaction app');
                // No HTTPS/localhost check; rely on browser's camera permission prompt
            },

            // QR Scanner Functions
            openQRScanner() {
                console.log('Opening QR scanner');
                this.isButtonDisabled = true;
                this.showQRScanner = true;
                this.$nextTick(() => {
                    this.startScanning();
                });
            },

            closeQRScanner() {
                console.log('Closing QR scanner');
                this.stopScanning();
                this.showQRScanner = false;
                this.cameraError = false;
                this.cameraErrorMessage = '';
                this.isButtonDisabled = false;
            },

            startScanning() {
                console.log('Starting QR scanner');
                this.scanActive = true;
                this.lastScanned = null;
                this.cameraError = false;
                this.cameraErrorMessage = '';

                this.video = document.getElementById('qr-video');
                this.canvas = document.getElementById('qr-canvas');
                this.ctx = this.canvas.getContext('2d');

                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }
                }).then(stream => {
                    console.log('Camera access granted');
                    this.video.srcObject = stream;
                    this.video.onloadedmetadata = () => {
                        this.video.play();
                        this.scanFrame();
                        this.isButtonDisabled = false;
                        console.log('Camera stream started');
                    };
                }).catch(err => {
                    this.scanActive = false;
                    this.cameraError = true;
                    this.isButtonDisabled = false;
                    console.error('Camera access error:', err);
                    if (err.name === 'NotAllowedError') {
                        this.cameraErrorMessage = 'Akses kamera ditolak. Harap izinkan akses kamera di pengaturan browser Anda.';
                    } else if (err.name === 'NotFoundError') {
                        this.cameraErrorMessage = 'Tidak ada kamera yang ditemukan. Pastikan perangkat Anda memiliki kamera yang aktif.';
                    } else if (err.name === 'NotReadableError') {
                        this.cameraErrorMessage = 'Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain dan coba lagi.';
                    } else {
                        this.cameraErrorMessage = 'Gagal mengakses kamera: ' + err.message;
                    }
                });
            },

            stopScanning() {
                if (this.scanActive && this.video && this.video.srcObject) {
                    const stream = this.video.srcObject;
                    const tracks = stream.getTracks();
                    tracks.forEach(track => track.stop());
                    this.video.srcObject = null;
                    this.scanActive = false;
                    console.log('Camera stream stopped');
                }
            },

            async scanFrame() {
                if (!this.scanActive || !this.video.videoWidth || !this.video.videoHeight) {
                    requestAnimationFrame(() => this.scanFrame());
                    return;
                }

                this.canvas.width = this.video.videoWidth;
                this.canvas.height = this.video.videoHeight;
                this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);

                const imageData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert'
                });

                if (code && code.data !== this.lastScanned) {
                    this.lastScanned = code.data;
                    console.log('Scanned QR code:', code.data);
                    const match = code.data.match(/\/inventory\/(\d+)/);
                    if (match && match[1]) {
                        const productId = match[1];
                        console.log('Extracted product ID:', productId);
                        try {
                            const response = await fetch(`/inventory/${productId}/json`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            console.log('Fetch response status:', response.status);
                            if (response.ok) {
                                const product = await response.json();
                                console.log('Fetched product data:', product);
                                if (product && product.stock > 0) {
                                    this.addToCart({
                                        id: product.id,
                                        name: product.name,
                                        color: product.color,
                                        size: product.size,
                                        selling_price: product.selling_price,
                                        stock: product.stock
                                    });
                                    console.log('Product added to cart from QR scanner');
                                    this.$nextTick(() => {
                                        setTimeout(() => {
                                            this.closeQRScanner();
                                        }, 1500);
                                    });
                                } else {
                                    alert('Produk tidak ditemukan atau stok habis!');
                                    console.warn('Product not found or out of stock:', product);
                                    this.lastScanned = null;
                                }
                            } else {
                                alert('Gagal mengambil data produk! Status: ' + response.status);
                                console.error('Fetch failed with status:', response.status);
                                this.lastScanned = null;
                            }
                        } catch (error) {
                            alert('Terjadi kesalahan saat mengambil data produk!');
                            console.error('Fetch error:', error);
                            this.lastScanned = null;
                        }
                    } else {
                        alert('Format QR tidak valid!');
                        console.warn('Invalid QR code format:', code.data);
                        this.lastScanned = null;
                    }
                }

                requestAnimationFrame(() => this.scanFrame());
            }
        };
    }
</script>
</body>
</html>