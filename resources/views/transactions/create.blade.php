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
<body class="bg-gray-100 font-sans">
    <!-- Form transaksi -->
    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="transactionApp()">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buat Transaksi Baru</h1>
            <p class="text-gray-600 mt-2">Tambahkan produk ke keranjang dan selesaikan transaksi.</p>
        </div>

        <!-- Form utama -->
        <form action="{{ route('transactions.store') }}" method="POST" @submit="validateForm($event)">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom kiri: Detail pelanggan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
                        
                        <div class="mb-4">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Masukkan nama pelanggan">
                        </div>
                        
                        <div class="mb-4">
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Contoh: 081234567890">
                        </div>
                        
                        <div class="mb-4">
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="customer_email" id="customer_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="email@example.com">
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                <option value="cash">Tunai</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Catatan tambahan tentang transaksi ini..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Scan QR Code -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Scan QR Code</h2>
                        <div class="flex items-center space-x-2 mb-4">
                            <input type="text" id="qr_scan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Kode QR Produk" x-model="qrCode" @keydown.enter.prevent="scanQR">
                            <button type="button" @click="scanQR" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pindai
                            </button>
                        </div>
                        <p class="text-sm text-gray-500">Pindai kode QR produk untuk menambahkannya ke transaksi secara cepat.</p>
                    </div>
                </div>
                
                <!-- Kolom tengah: Pemilihan produk -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Produk</h2>
                        
                        <div class="mb-4">
                            <label for="product_search" class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                            <div class="relative">
                                <input type="text" id="product_search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ketik nama produk..." x-model="searchQuery" @input="searchProducts">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hasil pencarian produk -->
                        <div class="mt-2 mb-4 max-h-64 overflow-y-auto" x-show="searchResults.length > 0" x-cloak>
                            <ul class="divide-y divide-gray-200">
                                <template x-for="product in searchResults" :key="product.id">
                                    <li class="py-2">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="font-medium text-gray-800" x-text="product.name"></p>
                                                <p class="text-sm text-gray-500">
                                                    <span x-text="product.color"></span> | 
                                                    <span x-text="product.size"></span> | 
                                                    <span class="font-semibold" x-text="'Stok: ' + product.stock"></span>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-bold text-blue-600" x-text="formatRupiah(product.selling_price)"></p>
                                                <button type="button" @click="addToCart(product)" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 font-medium py-1 px-2 rounded mt-1 transition-colors duration-150 ease-in-out">
                                                    + Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        
                        <div class="mt-4" x-show="searchQuery && searchResults.length === 0" x-cloak>
                            <p class="text-gray-500 text-center py-3">Tidak ada produk yang ditemukan</p>
                        </div>
                        
                        <!-- Daftar produk -->
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Produk Tersedia</h3>
                            <div class="max-h-96 overflow-y-auto border rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <template x-for="product in availableProducts" :key="product.id">
                                        <li class="p-3 hover:bg-gray-50">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-800" x-text="product.name"></p>
                                                    <p class="text-sm text-gray-500">
                                                        <span x-text="product.color"></span> | 
                                                        <span x-text="product.size"></span> | 
                                                        <span class="font-semibold" x-text="'Stok: ' + product.stock"></span>
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-bold text-blue-600" x-text="formatRupiah(product.selling_price)"></p>
                                                    <button type="button" @click="addToCart(product)" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-800 font-medium py-1 px-2 rounded mt-1 transition-colors duration-150 ease-in-out">
                                                        + Tambah
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom kanan: Keranjang dan checkout -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Keranjang Belanja</h2>
                        
                        <div x-show="cart.length === 0" class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p>Keranjang belanja kosong</p>
                            <p class="text-sm mt-1">Tambahkan produk dari daftar di samping</p>
                        </div>
                        
                        <div x-show="cart.length > 0" class="space-y-4 max-h-96 overflow-y-auto mb-4">
                            <template x-for="(item, index) in cart" :key="index">
                                <div class="border rounded-md p-3 relative">
                                    <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    
                                    <p class="font-medium text-gray-800" x-text="item.name"></p>
                                    <p class="text-sm text-gray-500 mb-2">
                                        <span x-text="item.color"></span> | 
                                        <span x-text="item.size"></span>
                                    </p>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center">
                                            <button type="button" @click="decrementQuantity(index)" class="bg-gray-200 text-gray-700 hover:bg-gray-300 h-6 w-6 rounded-l flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <input type="number" x-model.number="item.quantity" min="1" :max="item.stock" class="h-6 w-12 text-center border-y border-gray-200 focus:outline-none focus:ring-0 focus:border-gray-300" @change="updateQuantity(index)">
                                            <button type="button" @click="incrementQuantity(index)" class="bg-gray-200 text-gray-700 hover:bg-gray-300 h-6 w-6 rounded-r flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="font-bold text-blue-600" x-text="formatRupiah(item.selling_price)"></p>
                                    </div>
                                    
                                    <div class="mt-2 flex justify-end">
                                        <p class="text-sm font-medium text-gray-700">Subtotal: <span class="font-bold" x-text="formatRupiah(item.selling_price * item.quantity)"></span></p>
                                    </div>
                                    
                                    <input type="hidden" :name="'products['+index+'][id]'" x-model="item.id">
                                    <input type="hidden" :name="'products['+index+'][quantity]'" x-model="item.quantity">
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Ringkasan transaksi -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Transaksi</h2>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Harga</span>
                                <span class="font-medium" x-text="formatRupiah(calculateSubtotal())"></span>
                            </div>
                            
                            <!-- Diskon -->
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Diskon</span>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="discount_amount" id="discount_amount" class="text-right w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="discount" min="0">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                            </div>
                            
                            <!-- PPN 11% -->
                            <div class="flex justify-between border-t border-gray-200 pt-2">
                                <span class="text-gray-600">PPN (11%)</span>
                                <span class="font-medium" x-text="formatRupiah(calculateTax())"></span>
                            </div>
                            
                            <!-- Total Bayar -->
                            <div class="flex justify-between border-t border-b border-gray-200 py-2 mt-2">
                                <span class="text-gray-800 font-bold">Total Bayar</span>
                                <span class="text-blue-600 font-bold text-lg" x-text="formatRupiah(calculateTotal())"></span>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150 ease-in-out" :disabled="cart.length === 0">
                            Proses Transaksi
                        </button>
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
                
                // Format number to currency
                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },
                
                // Search products based on input
                searchProducts() {
                    if (!this.searchQuery.trim()) {
                        this.searchResults = [];
                        return;
                    }
                    
                    const query = this.searchQuery.toLowerCase();
                    this.searchResults = this.availableProducts.filter(product => 
                        product.name.toLowerCase().includes(query) || 
                        product.color.toLowerCase().includes(query) ||
                        product.size.toLowerCase().includes(query)
                    );
                },
                
                // Add product to cart
                addToCart(product) {
                    // Check if product already in cart
                    const existingItemIndex = this.cart.findIndex(item => item.id === product.id);
                    
                    if (existingItemIndex >= 0) {
                        // Increment quantity if still within stock limits
                        if (this.cart[existingItemIndex].quantity < product.stock) {
                            this.cart[existingItemIndex].quantity++;
                        } else {
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        // Add new item to cart
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
                    
                    // Clear search after adding
                    this.searchQuery = '';
                    this.searchResults = [];
                },
                
                // Remove item from cart
                removeItem(index) {
                    this.cart.splice(index, 1);
                },
                
                // Increment quantity of an item
                incrementQuantity(index) {
                    if (this.cart[index].quantity < this.cart[index].stock) {
                        this.cart[index].quantity++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                },
                
                // Decrement quantity of an item
                decrementQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    }
                },
                
                // Update quantity with validation
                updateQuantity(index) {
                    let qty = this.cart[index].quantity;
                    
                    // Ensure quantity is at least 1
                    if (qty < 1) {
                        this.cart[index].quantity = 1;
                    }
                    
                    // Ensure quantity doesn't exceed stock
                    if (qty > this.cart[index].stock) {
                        this.cart[index].quantity = this.cart[index].stock;
                        alert('Kuantitas disesuaikan dengan stok yang tersedia');
                    }
                },
                
                // Calculate subtotal (before tax and discount)
                calculateSubtotal() {
                    return this.cart.reduce((total, item) => {
                        return total + (item.selling_price * item.quantity);
                    }, 0);
                },
                
                // Calculate tax amount
                calculateTax() {
                    const subtotalAfterDiscount = this.calculateSubtotal() - this.discount;
                    return Math.max(0, subtotalAfterDiscount) * 0.11;
                },
                
                // Calculate grand total
                calculateTotal() {
                    return this.calculateSubtotal() - this.discount + this.calculateTax();
                },
                
                // Scan QR code
                scanQR() {
                    if (!this.qrCode) return;
                    
                    // Parse QR code (assuming format: "productId-brand-model")
                    const parts = this.qrCode.split('-');
                    if (parts.length > 0) {
                        const productId = parts[0];
                        
                        // Find product by ID
                        const product = this.availableProducts.find(p => p.id == productId);
                        if (product) {
                            this.addToCart(product);
                            this.qrCode = '';
                        } else {
                            alert('Produk tidak ditemukan!');
                        }
                    } else {
                        alert('Format QR Code tidak valid!');
                    }
                },
                
                // Validate form before submission
                validateForm(event) {
                    if (this.cart.length === 0) {
                        event.preventDefault();
                        alert('Keranjang belanja kosong! Tambahkan produk terlebih dahulu.');
                        return false;
                    }
                    
                    if (!document.getElementById('payment_method').value) {
                        event.preventDefault();
                        alert('Silakan pilih metode pembayaran!');
                        return false;
                    }
                    
                    return true;
                }
            }
        }
    </script>
</body>
</html>