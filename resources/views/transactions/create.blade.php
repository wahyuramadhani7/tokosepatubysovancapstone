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
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="transactionApp()">
    <div class="container mx-auto p-4 max-w-7xl">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Buat Transaksi Baru</h1>
            <p class="text-gray-600 text-sm">Tambahkan produk dan selesaikan transaksi.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Informasi Pelanggan -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Pelanggan</h2>
                    <div class="space-y-3">
                        <div>
                            <input type="text" name="customer_name" placeholder="Nama Pelanggan" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <input type="text" name="customer_phone" placeholder="No. Telepon" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <input type="email" name="customer_email" placeholder="Email" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <select name="payment_method" id="payment_method" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500" required>
                                <option value="" disabled selected>Metode Pembayaran</option>
                                <option value="cash">Tunai</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div>
                            <textarea name="notes" rows="2" placeholder="Catatan" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                    <!-- Scan QR -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-4 mb-3">Scan QR</h2>
                    <div class="flex gap-2">
                        <input type="text" x-model="qrCode" @keydown.enter.prevent="scanQR" placeholder="Kode QR" class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <button type="button" @click="scanQR" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Pindai</button>
                    </div>
                </div>

                <!-- Pilih Produk -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Produk</h2>
                    <div class="mb-3">
                        <input type="text" x-model="searchQuery" @input="searchProducts" placeholder="Cari Produk..." class="w-full border rounded p-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <!-- Hasil Pencarian -->
                    <div x-show="searchResults.length > 0" class="max-h-40 overflow-y-auto mb-3" x-cloak>
                        <ul class="divide-y divide-gray-200">
                            <template x-for="product in searchResults" :key="product.id">
                                <li class="py-2 flex justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800" x-text="product.name"></p>
                                        <p class="text-xs text-gray-500" x-text="`${product.color} | ${product.size} | Stok: ${product.stock}`"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600" x-text="formatRupiah(product.selling_price)"></p>
                                        <button type="button" @click="addToCart(product)" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-2 rounded">+ Tambah</button>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <!-- Daftar Produk -->
                    <div class="max-h-64 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            <template x-for="product in availableProducts" :key="product.id">
                                <li class="py-2 flex justify-between hover:bg-gray-50">
                                    <div>
                                        <p class="font-medium text-gray-800" x-text="product.name"></p>
                                        <p class="text-xs text-gray-500" x-text="`${product.color} | ${product.size} | Stok: ${product.stock}`"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600" x-text="formatRupiah(product.selling_price)"></p>
                                        <button type="button" @click="addToCart(product)" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-2 rounded">+ Tambah</button>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Keranjang dan Checkout -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Keranjang</h2>
                    <div x-show="cart.length === 0" class="text-center py-6 text-gray-500">
                        <svg class="h-10 w-10 mx-auto text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p>Keranjang kosong</p>
                    </div>
                    <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto mb-3">
                        <template x-for="(item, index) in cart" :key="index">
                            <div class="border rounded p-2 mb-2 relative">
                                <button type="button" @click="removeItem(index)" class="absolute top-1 right-1 text-gray-400 hover:text-red-500">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <p class="font-medium text-gray-800" x-text="item.name"></p>
                                <p class="text-xs text-gray-500" x-text="`${item.color} | ${item.size}`"></p>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="flex items-center">
                                        <button type="button" @click="decrementQuantity(index)" class="bg-gray-200 h-6 w-6 rounded-l">-</button>
                                        <input type="number" x-model.number="item.quantity" min="1" :max="item.stock" class="h-6 w-10 text-center border-y" @change="updateQuantity(index)">
                                        <button type="button" @click="incrementQuantity(index)" class="bg-gray-200 h-6 w-6 rounded-r">+</button>
                                    </div>
                                    <p class="font-bold text-blue-600" x-text="formatRupiah(item.selling_price * item.quantity)"></p>
                                </div>
                                <input type="hidden" :name="'products['+index+'][id]'" x-model="item.id">
                                <input type="hidden" :name="'products['+index+'][quantity]'" x-model="item.quantity">
                            </div>
                        </template>
                    </div>
                    <!-- Ringkasan -->
                    <div class="border-t pt-3">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Ringkasan</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span x-text="formatRupiah(calculateSubtotal())"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diskon</span>
                                <input type="number" name="discount_amount" x-model="discount" min="0" class="w-20 text-right border rounded p-1 text-sm">
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-bold">Total</span>
                                <span class="font-bold text-blue-600" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700" :disabled="cart.length === 0">Proses Transaksi</button>
                    </div>
                </div>
            </div>
        </form>
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

                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
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
                            alert('Stok tidak cukup!');
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
                        alert('Stok tidak cukup!');
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
                        alert('Kuantitas disesuaikan dengan stok');
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
                        alert('Keranjang kosong! Tambahkan produk.');
                        return false;
                    }
                    if (!document.getElementById('payment_method').value) {
                        event.preventDefault();
                        alert('Pilih metode pembayaran!');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
</body>
</html>