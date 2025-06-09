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
        button:focus, input:focus, select:focus, textarea:focus {
            outline: 2px solid #1E1E1E;
            outline-offset: 2px;
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
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
                        },
                        accent: {
                            500: '#10b981',
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 12px rgba(0, 0, 0, 0.08)',
                        'softer': '0 6px 20px rgba(0, 0, 0, 0.06)',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="transactionApp()" x-init="initialize()">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-dark-800 text-white shadow-soft">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <div>
                        <h1 class="font-bold text-2xl tracking-tight">Sepatu by Sovan</h1>
                        <p class="text-sm text-gray-300">Premium Footwear Collection</p>
                    </div>
                </div>
                <a href="{{ url('/transactions') }}" class="bg-dark-700 hover:bg-dark-600 text-white px-4 py-2 rounded-lg transition-all flex items-center space-x-2">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-dark-800 tracking-tight">Buat Transaksi Baru</h1>
                <p class="text-gray-600 mt-2 text-lg">Pilih produk premium dan selesaikan transaksi dengan mudah</p>
            </div>

            <!-- Form -->
            <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Product Selection -->
                    <div class="bg-white rounded-xl shadow-softer p-6 card-hover">
                        <h2 class="text-xl font-semibold text-dark-800 mb-5 flex items-center">
                            <svg class="h-6 w-6 mr-2 text-accent-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Pilih Produk
                        </h2>

                        <div class="relative mb-5">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input="searchProducts" placeholder="Cari nama, warna, atau ukuran produk..." class="w-full border border-gray-200 rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                        </div>

                        <!-- Search Results -->
                        <div x-show="searchResults.length > 0" class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5 max-h-60 overflow-y-auto animate-slide-in" x-cloak>
                            <ul class="divide-y divide-gray-100">
                                <template x-for="product in searchResults" :key="product.id">
                                    <li class="p-4 hover:bg-gray-50 transition-colors cursor-pointer" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-dark-800" x-text="product.name"></p>
                                                <div class="flex items-center mt-2 space-x-2">
                                                    <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-sm text-gray-500" x-text="product.color"></span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-sm text-gray-500" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-sm" :class="product.stock > 5 ? 'text-accent-600' : 'text-orange-500'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-dark-800" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-2 text-sm bg-accent-500 hover:bg-accent-600 text-white py-1 px-3 rounded-full transition-colors">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Product List -->
                        <div class="border border-gray-200 rounded-lg max-h-96 overflow-y-auto">
                            <div x-show="availableProducts.length === 0" class="text-center py-12 text-gray-500">
                                <svg class="h-12 w-12 mx-auto text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-lg">Tidak ada produk tersedia</p>
                                <p class="text-sm text-gray-400">Coba lagi nanti</p>
                            </div>
                            <ul class="divide-y divide-gray-100">
                                <template x-for="product in availableProducts" :key="product.id">
                                    <li class="p-4 hover:bg-gray-50 transition-colors cursor-pointer animate-slide-in" @click="addToCart(product)">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-dark-800" x-text="product.name"></p>
                                                <div class="flex items-center mt-2 space-x-2">
                                                    <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(product.color)}`"></span>
                                                    <span class="text-sm text-gray-500" x-text="product.color"></span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-sm text-gray-500" x-text="`Ukuran: ${product.size}`"></span>
                                                    <span class="text-gray-300">|</span>
                                                    <span class="text-sm" :class="product.stock > 5 ? 'text-accent-600' : 'text-orange-500'" x-text="`Stok: ${product.stock}`"></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-dark-800" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" class="mt-2 text-sm bg-accent-500 hover:bg-accent-600 text-white py-1 px-3 rounded-full transition-colors">+ Tambah</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Customer & Cart -->
                    <div class="bg-white rounded-xl shadow-softer p-6 card-hover">
                        <!-- Customer Info -->
                        <h2 class="text-xl font-semibold text-dark-800 mb-5 flex items-center">
                            <svg class="h-6 w-6 mr-2 text-accent-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Pelanggan
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-dark-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama pelanggan" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-700 mb-1">No. Telepon</label>
                                <input type="text" name="customer_phone" placeholder="Contoh: 081234567890" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-700 mb-1">Email</label>
                                <input type="email" name="customer_email" placeholder="email@example.com" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-dark-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all" required>
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-dark-700 mb-1">Catatan</label>
                                <textarea name="notes" rows="3" placeholder="Catatan tambahan untuk transaksi" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all"></textarea>
                            </div>
                        </div>

                        <!-- Cart -->
                        <h2 class="text-xl font-semibold text-dark-800 mb-5 flex items-center border-t pt-5">
                            <svg class="h-6 w-6 mr-2 text-accent-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Keranjang Belanja
                        </h2>

                        <div x-show="cart.length === 0" class="text-center py-10 text-gray-500 border border-gray-200 rounded-lg bg-gray-50 mb-5">
                            <svg class="h-14 w-14 mx-auto text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-lg font-medium">Keranjang Kosong</p>
                            <p class="text-sm text-gray-400">Tambahkan produk dari daftar di samping</p>
                        </div>

                        <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto mb-5 space-y-4">
                            <ul class="space-y-3">
                                <template x-for="(item, index) in cart" :key="index">
                                    <li class="border border-gray-200 rounded-lg p-4 bg-gray-50 relative card-hover animate-slide-in">
                                        <button type="button" @click="removeItem(index)" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <p class="font-semibold text-dark-800 pr-8" x-text="item.name"></p>
                                        <div class="flex items-center mt-2 space-x-2">
                                            <span class="inline-block h-3 w-3 rounded-full" :style="`background-color: ${getColorCode(item.color)}`"></span>
                                            <span class="text-sm text-gray-500" x-text="item.color"></span>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-sm text-gray-500" x-text="`Ukuran: ${item.size}`"></span>
                                        </div>
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                <button type="button" @click="decrementQuantity(index)" class="bg-gray-100 hover:bg-gray-200 px-3 py-2 transition-colors">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" x-model.number="item.quantity" min="1" :max="item.stock" class="w-12 text-center border-x py-2 text-sm focus:ring-2 focus:ring-accent-500" @change="updateQuantity(index)">
                                                <button type="button" @click="incrementQuantity(index)" class="bg-gray-100 hover:bg-gray-200 px-3 py-2 transition-colors">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="font-semibold text-dark-800" x-text="formatRupiah(item.selling_price * item.quantity)"></p>
                                        </div>
                                        <input type="hidden" :name="'products['+index+'][id]'" x-model="item.id">
                                        <input type="hidden" :name="'products['+index+'][quantity]'" x-model="item.quantity">
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-200 pt-5">
                            <h2 class="text-xl font-semibold text-dark-800 mb-4">Ringkasan Pembayaran</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold text-dark-800" x-text="formatRupiah(calculateSubtotal())"></span>
                                </div>
                                <div class="flex justify-between items-center text-lg">
                                    <span class="text-gray-600">Diskon</span>
                                    <div class="flex items-center">
                                        <span class="text-gray-500 mr-2">Rp</span>
                                        <input type="number" name="discount_amount" x-model="discount" min="0" class="w-28 text-right border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                                    </div>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4 text-lg">
                                    <span class="font-bold text-dark-800">Total Bayar</span>
                                    <span class="font-bold text-xl text-accent-500" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 py-3 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-semibold transition-all flex items-center justify-center" :disabled="cart.length === 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0}">
                                <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            }
        };
    }
    </script>
</body>
</html>