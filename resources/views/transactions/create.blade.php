<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#FBF8E9',
                            100: '#F7F1D3',
                            200: '#F0E4A7',
                            300: '#E9D77B',
                            400: '#E3CA4F',
                            500: '#DCBD23',
                            600: '#B0971C',
                            700: '#847115',
                            800: '#574B0E',
                            900: '#2B2507',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans" x-data="transactionApp()">
    <div class="min-h-screen flex flex-col">
        <!-- Header/Navigation -->
        <header class="bg-gradient-to-r from-black to-gray-800 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <div>
                        <h1 class="font-bold text-xl">Sepatu by Sovan</h1>
                        <p class="text-xs text-gold-300">Premium Footwear Collection</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ url('/transactions') }}" class="text-sm bg-black bg-opacity-40 hover:bg-opacity-60 border border-gold-400 px-3 py-2 rounded-lg transition-all flex items-center">
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
                    <div class="bg-white rounded-xl shadow-md p-5">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <input type="text" x-model="searchQuery" @input="searchProducts" placeholder="Cari produk, warna, atau ukuran..." class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                        </div>
                        
                        <!-- Scan QR -->
                        <div class="flex gap-2 mb-4">
                            <input type="text" x-model="qrCode" @keydown.enter.prevent="scanQR" placeholder="Masukkan kode QR" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                            <button type="button" @click="scanQR" class="px-4 py-2 bg-black text-gold-400 rounded-lg hover:bg-gray-900 transition-colors">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- QR Scanner Button -->
                        <button type="button" @click="openQRScanner" class="w-full mb-4 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buka Kamera QR Scanner
                        </button>
                        
                        <!-- Hasil Pencarian -->
                        <div x-show="searchResults.length > 0" class="bg-white border rounded-lg shadow-sm mb-4 max-h-48 overflow-y-auto" x-cloak>
                            <ul class="divide-y divide-gray-100">
                                <template x-for="product in searchResults" :key="product.id">
                                    <li class="p-3 hover:bg-gold-50 transition-colors cursor-pointer" @click="addToCart(product)">
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
                                                <button type="button" class="mt-1 text-xs bg-gold-100 hover:bg-gold-200 text-gold-700 py-1 px-2 rounded-full transition-colors">+ Tambah</button>
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
                                    <li class="p-3 hover:bg-gold-50 transition-colors cursor-pointer" @click="addToCart(product)">
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
                                                <button type="button" class="mt-1 text-xs bg-gold-100 hover:bg-gold-200 text-gold-700 py-1 px-2 rounded-full transition-colors">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Informasi Pelanggan & Keranjang (Gabungan) -->
                    <div class="bg-white rounded-xl shadow-md p-5">
                        <!-- Informasi Pelanggan -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Contoh: 081234567890" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="customer_email" placeholder="email@example.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500" required>
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="notes" rows="2" placeholder="Catatan tambahan untuk transaksi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500"></textarea>
                            </div>
                        </div>
                        
                        <!-- Keranjang Belanja -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-t pt-4">
                            <svg class="h-5 w-5 mr-2 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                        <input type="number" name="discount_amount" x-model="discount" min="0" class="w-24 text-right border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-3 mt-3">
                                    <span class="font-bold text-gray-800">Total Bayar</span>
                                    <span class="font-bold text-lg text-gold-600" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-5 py-3 bg-black hover:bg-gray-900 text-gold-400 rounded-lg font-medium transition-colors flex items-center justify-center" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
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

        <!-- Footer -->
        <footer class="bg-black text-white py-6 mt-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="font-bold">Sepatu by Sovan</span>
                        </div>
                        <p class="text-gray-400 text-sm mt-1">Premium Footwear Collection</p>
                    </div>
                    <div class="text-sm text-gray-400">
                        &copy; 2025 Sepatu by Sovan. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- QR Scanner Modal -->
        <div x-show="showQRScanner" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white rounded-xl p-5 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">QR Code Scanner</h3>
                    <button @click="closeQRScanner" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="bg-gray-100 rounded-lg overflow-hidden relative" style="height: 300px;">
                    <video id="qr-video" class="w-full h-full object-cover"></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-2/3 h-2/3 border-2 border-gold-500 rounded-lg opacity-70"></div>
                    </div>
                </div>
                
                <div class="mt-4 text-center text-sm text-gray-600">
                    <p x-show="!scanActive">Klik tombol di bawah untuk mulai memindai</p>
                    <p x-show="scanActive">Arahkan kamera ke QR Code produk...</p>
                    <p x-show="lastScanned" class="text-green-600 font-medium mt-2">
                        Berhasil memindai produk!
                    </p>
                </div>
                
                <div class="mt-4 flex justify-center">
                    <button type="button" @click="startScanning" x-show="!scanActive" class="px-4 py-2 bg-black text-gold-400 rounded-lg hover:bg-gray-900 transition-colors">
                        Mulai Pemindaian
                    </button>
                    <button type="button" @click="stopScanning" x-show="scanActive" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hentikan Pemindaian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import QR Scanner Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
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
                scanner: null,
                lastScanned: null,

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
                    }
                    this.searchQuery = '';
                    this.searchResults = [];
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                },

                incrementQuantity(index) {
                    if (this.cart[index].quantity < this.cart[index].stock) {
                        this.cart[index].quantity++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                },

                decrementQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    }
                },

                updateQuantity(index) {
                    let qty = this.cart[index].quantity;
                    if (qty < 1) this.cart[index].quantity = 1;
                    if (qty > this.cart[index].stock) {
                        this.cart[index].quantity = this.cart[index].stock;
                        alert('Kuantitas disesuaikan dengan stok tersedia');
                    }
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => total + (item.selling_price * item.quantity), 0);
                },

                calculateTotal() {
                    return Math.max(0, this.calculateSubtotal() - this.discount);
                },

                scanQR() {
                    if (!this.qrCode) return;
                    const parts = this.qrCode.split('-');
                    if (parts.length > 0) {
                        const product = this.availableProducts.find(p => p.id == parts[0]);
                        if (product) {
                            this.addToCart(product);
                            this.qrCode = '';
                        } else {
                            alert('Produk tidak ditemukan!');
                        }
                    } else {
                        alert('Format QR tidak valid!');
                    }
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
                
                // QR Scanner Functions
                openQRScanner() {
                    this.showQRScanner = true;
                },
                
                closeQRScanner() {
                    this.showQRScanner = false;
                    this.stopScanning();
                },
                
                startScanning() {
                    this.scanActive = true;
                    this.lastScanned = null;
                    
                    const qrConfig = {
                        fps: 10,
                        qrbox: { width: 250, height: 250 },
                        rememberLastUsedCamera: true
                    };
                    
                    // Initialize the scanner
                    this.scanner = new Html5Qrcode("qr-video");
                    
                    // Start scanning
                    this.scanner.start(
                        { facingMode: "environment" }, // Use back camera
                        qrConfig,
                        this.onScanSuccess.bind(this),
                        this.onScanError.bind(this)
                    ).catch(err => {
                        console.error("Error starting scanner:", err);
                        alert("Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.");
                        this.scanActive = false;
                    });
                },
                
                stopScanning() {
                    if (this.scanner && this.scanActive) {
                        this.scanner.stop().then(() => {
                            this.scanActive = false;
                            console.log('Scanner stopped');
                        }).catch(err => {
                            console.error("Error stopping scanner:", err);
                        });
                    }
                },
                
                onScanSuccess(decodedText) {
                    console.log(`QR Code detected: ${decodedText}`);
                    this.lastScanned = decodedText;
                    
                    // Process the scanned QR code
                    const parts = decodedText.split('-');
                    if (parts.length > 0) {
                        const product = this.availableProducts.find(p => p.id == parts[0]);
                        if (product) {
                            this.addToCart(product);
                            
                            // Provide visual feedback
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    // Close scanner after successful scan
                                    this.closeQRScanner();
                                }, 1500);
                            });
                        } else {
                            alert('Produk tidak ditemukan!');
                        }
                    } else {
                        alert('Format QR tidak valid!');
                    }
                },
                
                onScanError(error) {
                    // We can ignore errors as they're usually just frames without QR codes
                    // console.error("QR scan error: ", error);
                }
            };
        }
    </script>
</body>
</html>