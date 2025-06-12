<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        
        /* Static gradient background */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
            padding-top: 6.5rem;
            color: #FFFFFF;
        }
        
        /* Fade-in animation */
        .fade-in { 
            animation: fadeIn 0.6s ease-out; 
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Gradient buttons with muted gold accent */
        .btn-gradient {
            background: linear-gradient(90deg, #2D2D2D 0%, #4A4A4A 100%);
            border: 1px solid rgba(212, 160, 23, 0.3);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(90deg, #3B3B3B 0%, #5A5A5A 100%);
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
        }
        
        /* Gradient header with gold border */
        .bg-gradient-header {
            background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
            border-bottom: 2px solid rgba(212, 160, 23, 0.3);
        }
        
        /* Glassmorphism card effect */
        .card-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }
        .card-glass:hover {
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.2);
        }
        
        /* Status badge styling */
        .status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s ease;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 160, 23, 0.5);
            border-radius: 12px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 160, 23, 0.7);
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
                            dark: {
                                50: '#F9FAFB',
                                100: '#E5E5E5',
                                200: '#D4D4D4',
                                300: '#A3A3A3',
                                400: '#737373',
                                500: '#525252',
                                600: '#404040',
                                700: '#2D2D2D',
                                800: '#1A1A1A',
                                900: '#0F0F0F',
                            },
                            gold: {
                                100: '#FFF7E6',
                                200: '#FFE4B5',
                                300: '#D4A017',
                                400: '#B28704',
                            }
                        }
                    },
                    boxShadow: {
                        'custom': '0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -2px rgba(0, 0, 0, 0.1)',
                        'lg': '0 12px 20px -5px rgba(0, 0, 0, 0.3)',
                        'glow': '0 0 15px rgba(212, 160, 23, 0.3)',
                    },
                    transitionProperty: {
                        'height': 'height',
                        'spacing': 'margin, padding',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()">
    <!-- Header/Navigation -->
    <header class="fixed top-0 w-full bg-gradient-header text-white shadow-lg z-50">
        <div class="container mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-brand-gold-100 rounded-full p-3 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-brand-dark-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Playfair_Display'] font-bold text-3xl tracking-tight text-brand-gold-200">Sepatu by Sovan</h1>
                    <p class="text-sm text-gray-300 font-light">Luxury Footwear Collection</p>
                </div>
            </div>
            <div>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="inline-flex items-center px-6 py-3 rounded-2xl btn-gradient text-white font-semibold shadow-lg hover:shadow-glow transition-all duration-300">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12 max-w-7xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-10 bg-brand-dark-800 border border-brand-gold-300 text-white p-6 rounded-2xl shadow-lg fade-in flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <div class="bg-brand-gold-300 rounded-full p-2.5 mr-4">
                    <svg class="h-7 w-7 text-brand-dark-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-semibold text-base">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" target="_blank" class="text-sm text-brand-dark-900 bg-brand-gold-300 hover:bg-brand-gold-400 px-6 py-3 rounded-2xl flex items-center font-semibold transition-all shadow-md">
                <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </a>
            @endif
            <button @click="dismissAlert" class="text-brand-gold-200 hover:text-brand-gold-300 p-2.5 rounded-full hover:bg-brand-dark-700 transition-colors">
                <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="card-glass rounded-2xl shadow-lg mb-10 p-10 border border-brand-gold-300/30">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-8 lg:mb-0">
                    <h1 class="font-['Playfair_Display'] text-5xl font-bold text-white mb-3 flex items-center">
                        <svg class="h-12 w-12 mr-4 text-brand-gold-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Daftar Transaksi
                    </h1>
                    <p class="text-gray-300 text-lg">Kelola transaksi penjualan dengan gaya dan kemudahan</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('transactions.report') }}" target="_blank" class="px-6 py-3.5 bg-brand-dark-800 border border-brand-gold-300/50 rounded-2xl text-gray-200 hover:bg-brand-dark-700 shadow-custom transition-all flex items-center font-semibold text-sm">
                        <svg class="h-6 w-6 mr-3 text-brand-gold-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan
                    </a>
                    <a href="{{ route('transactions.create') }}" class="px-6 py-3.5 rounded-2xl btn-gradient text-white flex items-center font-semibold text-sm shadow-lg hover:shadow-glow">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card-glass rounded-2xl shadow-lg mb-10 border border-brand-gold-300/30 overflow-hidden">
            <div class="px-8 py-6 flex justify-between items-center border-b border-brand-gold-300/20">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="h-6 w-6 mr-3 text-brand-gold-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-300 hover:text-brand-gold-300 p-2 rounded-full hover:bg-brand-dark-700 transition-colors">
                    <svg x-show="showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-8 bg-gradient-to-br from-brand-dark-800/60 to-brand-dark-900/60">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-200 mb-2">Tanggal</label>
                        <input type="date" x-model="filterDate" @change="filterTransactions" class="w-full border border-brand-gold-300/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-gold-300 focus:border-brand-gold-300 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-200 mb-2">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="filterTransactions" class="w-full border border-brand-gold-300/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-gold-300 focus:border-brand-gold-300 shadow-sm transition-all">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-200 mb-2">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="filterTransactions" class="w-full border border-brand-gold-300/30 bg-brand-dark-800 text-white rounded-2xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-gold-300 focus:border-brand-gold-300 shadow-sm transition-all">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="resetFilters" class="px-6 py-3 bg-brand-dark-800 border border-brand-gold-300/50 rounded-2xl text-gray-200 hover:bg-brand-dark-700 transition-all flex items-center justify-center text-sm font-semibold shadow-sm">
                        <svg class="h-5 w-5 mr-2 text-brand-gold-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card-glass rounded-2xl shadow-lg overflow-hidden border border-brand-gold-300/30">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-gold-300/20">
                    <thead>
                        <tr class="bg-gradient-to-r from-brand-dark-800 to-brand-dark-900">
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID 
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-2"></span>
                                </div>
                            </th>
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Tanggal
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-2"></span>
                                </div>
                            </th>
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider">Metode</th>
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider cursor-pointer" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-2"></span>
                                </div>
                            </th>
                            <th class="px-8 py-5 text-left text-xs font-semibold text-gray-200 uppercase tracking-wider">Status</th>
                            <th class="px-8 py-5 text-right text-xs font-semibold text-gray-200 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-gold-300/20" x-show="filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-dark-800/60': isNewTransaction(transaction.id)}" class="hover:bg-brand-dark-700/60 transition-colors duration-200">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-white bg-brand-dark-700 px-3 py-1.5 rounded-2xl" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-300" x-text="formatDate(transaction.created_at || transaction.date)"></span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-white" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-xs text-gray-400" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-900/60 text-green-200 border-green-300/30': transaction.payment_method === 'cash',
                                        'bg-gray-900/60 text-gray-200 border-gray-300/30': transaction.payment_method === 'credit_card',
                                        'bg-blue-900/60 text-blue-200 border-blue-300/30': transaction.payment_method === 'transfer'
                                    }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-brand-gold-200" x-text="formatRupiah(transaction.final_amount || transaction.total)"></span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-900/60 text-green-200 border-green-300/30': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                        'bg-yellow-900/60 text-yellow-200 border-yellow-300/30': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                        'bg-red-900/60 text-red-200 border-red-300/30': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')"></span>
                                </td>
                                <td class="px-8 py-5 text-right space-x-3 whitespace-nowrap">
                                    <a :href="'{{ url('/transactions') }}/' + transaction.id + '/print'" target="_blank" class="inline-flex items-center p-2.5 bg-brand-dark-700 text-gray-200 rounded-2xl hover:bg-brand-dark-600 transition-colors">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    <button @click="confirmDelete(transaction)" class="inline-flex items-center p-2.5 bg-red-900/60 text-red-200 rounded-2xl hover:bg-red-800/60 transition-colors">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div x-show="filteredTransactions.length === 0" class="text-center py-24">
                    <div class="bg-brand-dark-800 rounded-full h-32 w-32 flex items-center justify-center mx-auto mb-8 border-2 border-dashed border-brand-gold-300/50">
                        <svg class="h-16 w-16 text-brand-gold-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-['Playfair_Display'] text-3xl font-bold text-white mb-4">Tidak Ada Transaksi</h3>
                    <p class="text-gray-300 max-w-md mx-auto text-lg">Belum ada transaksi yang tersedia atau sesuai dengan filter yang Anda tentukan.</p>
                    <div class="mt-8">
                        <button @click="resetFilters" class="btn-gradient text-white px-6 py-3 rounded-2xl shadow-lg flex items-center mx-auto text-sm font-semibold">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="filteredTransactions.length > 0" class="flex flex-col md:flex-row items-center justify-between px-8 py-6 bg-brand-dark-800/60 border-t border-brand-gold-300/20">
                <div class="text-sm text-gray-300 mb-4 md:mb-0">
                    Menampilkan <span class="font-semibold text-white" x-text="paginationFrom()"></span> - <span class="font-semibold text-white" x-text="paginationTo()"></span> dari <span class="font-semibold text-white" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center space-x-3">
                    <div class="mr-3">
                        <select x-model.number="perPage" class="border border-brand-gold-300/30 bg-brand-dark-800 text-white rounded-2xl p-2.5 text-sm shadow-sm focus:ring-2 focus:ring-brand-gold-300 focus:border-brand-gold-300">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                    <button @click="prevPage" :disabled="currentPage === 1" class="p-2.5 border border-brand-gold-300/30 rounded-2xl text-sm bg-brand-dark-800 shadow-sm" :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-brand-dark-700 hover:border-brand-gold-300': currentPage !== 1}">
                        <svg class="h-5 w-5 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <template x-for="page in displayedPages()" :key="page">
                        <button x-show="page !== '...'" @click="goToPage(page)" class="px-4 py-2 border text-sm rounded-2xl shadow-sm" :class="page === currentPage ? 'bg-brand-gold-300 text-brand-dark-900 border-brand-gold-300' : 'bg-brand-dark-800 text-gray-200 border-brand-gold-300/30 hover:bg-brand-dark-700'">
                            <span x-text="page"></span>
                        </button>
                        <span x-show="page === '...'" class="px-3 py-2 text-gray-400">...</span>
                    </template>
                    <button @click="nextPage" :disabled="currentPage >= totalPages" class="p-2.5 border border-brand-gold-300/30 rounded-2xl text-sm bg-brand-dark-800 shadow-sm" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages, 'hover:bg-brand-dark-700 hover:border-brand-gold-300': currentPage < totalPages}">
                        <svg class="h-5 w-5 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50" x-cloak>
        <div @click.away="showDeleteModal = false" class="card-glass rounded-2xl max-w-md w-full p-8 shadow-2xl border border-brand-gold-300/30 fade-in">
            <div class="flex items-center mb-6">
                <div class="bg-red-900/60 rounded-full p-4 mr-4 flex-shrink-0">
                    <svg class="h-8 w-8 text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-['Playfair_Display'] text-2xl font-bold text-white">Konfirmasi Hapus</h3>
                    <p class="text-gray-300 mt-2 text-sm">Yakin ingin menghapus transaksi <span class="font-semibold text-brand-gold-200" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>?</p>
                </div>
            </div>
            <p class="text-gray-400 text-sm p-4 bg-brand-dark-800/60 rounded-2xl mb-6 border border-brand-gold-300/20">
                Tindakan ini tidak dapat dibatalkan dan semua data terkait transaksi ini akan dihapus secara permanen.
            </p>
            <div class="flex justify-end space-x-4">
                <button @click="showDeleteModal = false" class="px-6 py-3 bg-brand-dark-800 hover:bg-brand-dark-700 text-gray-200 rounded-2xl transition-all font-semibold text-sm">
                    Batal
                </button>
                <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-red-900/60 hover:bg-red-800/60 text-red-200 rounded-2xl transition-all font-semibold text-sm shadow-md">
                        Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <div x-show="newTransactionId" class="fixed bottom-10 right-10" x-cloak>
        <a :href="'{{ url('/transactions') }}/' + newTransactionId + '/print'" target="_blank" class="flex items-center justify-center h-16 w-16 bg-brand-gold-300 text-brand-dark-900 rounded-full shadow-2xl hover:bg-brand-gold-400 transition-all duration-300">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
        </a>
    </div>

    <script>
        function transactionListApp() {
            return {
                transactions: @json($transactions->items() ?? []),
                filteredTransactions: [],
                filterDate: '',
                paymentMethodFilter: '',
                statusFilter: '',
                currentPage: 1,
                perPage: 10,
                sortColumn: 'id',
                sortDirection: 'desc',
                showDeleteModal: false,
                transactionToDelete: null,
                newTransactionId: @json(session('transaction_id') ?? null),

                init() {
                    const newTransaction = @json(session('new_transaction') ?? null);
                    if (newTransaction && !this.transactions.find(t => t.id === newTransaction.id)) {
                        this.transactions.unshift(newTransaction);
                    }
                    this.filterTransactions();
                    if (this.newTransactionId) {
                        this.$nextTick(() => this.highlightNewTransaction());
                    }
                },

                dismissAlert() {
                    const alert = document.querySelector('.fade-in');
                    if (alert) {
                        alert.remove();
                    }
                },

                isNewTransaction(id) {
                    return id == this.newTransactionId;
                },

                highlightNewTransaction() {
                    const index = this.transactions.findIndex(t => t.id == this.newTransactionId);
                    if (index >= 0) {
                        this.goToPage(Math.floor(index / this.perPage) + 1);
                        this.$nextTick(() => {
                            document.querySelector(`tr.bg-brand-dark-800/60`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                },

                formatRupiah(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                },

                translatePaymentMethod(method) {
                    return { cash: 'Tunai', credit_card: 'Kartu Kredit', transfer: 'Transfer Bank' }[method] || method;
                },

                translateStatus(status) {
                    return { paid: 'Lunas', completed: 'Selesai', pending: 'Pending', cancelled: 'Dibatalkan' }[status] || status;
                },

                resetFilters() {
                    this.filterDate = this.paymentMethodFilter = this.statusFilter = '';
                    this.filterTransactions();
                },

                filterTransactions() {
                    let results = [...this.transactions];

                    if (this.filterDate) {
                        const selectedDate = new Date(this.filterDate);
                        const selectedDateStr = selectedDate.toISOString().split('T')[0]; // Get YYYY-MM-DD format
                        
                        results = results.filter(t => {
                            const transactionDate = new Date(t.created_at || t.date);
                            const transactionDateStr = transactionDate.toISOString().split('T')[0];
                            return transactionDateStr === selectedDateStr;
                        });
                    }

                    if (this.paymentMethodFilter) {
                        results = results.filter(t => t.payment_method === this.paymentMethodFilter);
                    }

                    if (this.statusFilter) {
                        results = results.filter(t => t.payment_status === this.statusFilter || t.status === this.statusFilter);
                    }

                    // Sort results
                    this.sortResults(results);
                    this.filteredTransactions = results;
                    this.currentPage = 1;
                },

                sortResults(results) {
                    results.sort((a, b) => {
                        let aValue, bValue;
                        switch (this.sortColumn) {
                            case 'date':
                                aValue = new Date(a.created_at || a.date);
                                bValue = new Date(b.created_at || b.date);
                                break;
                            case 'total':
                                aValue = parseFloat(a.final_amount || a.total);
                                bValue = parseFloat(b.final_amount || b.total);
                                break;
                            default:
                                aValue = a.id;
                                bValue = b.id;
                        }
                        return this.sortDirection === 'asc' ? aValue - bValue : bValue - aValue;
                    });
                    return results;
                },

                sortBy(column) {
                    this.sortDirection = (this.sortColumn === column && this.sortDirection === 'asc') ? 'desc' : 'asc';
                    this.sortColumn = column;
                    this.filterTransactions();
                },

                get totalPages() {
                    return Math.ceil(this.filteredTransactions.length / this.perPage);
                },

                get paginatedTransactions() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredTransactions.slice(start, start + this.perPage);
                },

                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },

                goToPage(page) {
                    if (typeof page === 'number') this.currentPage = Math.min(Math.max(1, page), this.totalPages);
                },

                paginationFrom() {
                    return this.filteredTransactions.length ? (this.currentPage - 1) * this.perPage + 1 : 0;
                },

                paginationTo() {
                    return Math.min(this.currentPage * this.perPage, this.filteredTransactions.length);
                },

                displayedPages() {
                    if (this.totalPages <= 5) {
                        return Array.from({length: this.totalPages}, (_, i) => i + 1);
                    }
                    
                    let pages = [];
                    if (this.currentPage <= 3) {
                        pages = [1, 2, 3, 4, '...', this.totalPages];
                    } else if (this.currentPage >= this.totalPages - 2) {
                        pages = [1, '...', this.totalPages - 3, this.totalPages - 2, this.totalPages - 1, this.totalPages];
                    } else {
                        pages = [1, '...', this.currentPage - 1, this.currentPage, this.currentPage + 1, '...', this.totalPages];
                    }
                    return pages;
                },

                confirmDelete(transaction) {
                    this.transactionToDelete = transaction;
                    this.showDeleteModal = true;
                }
            };
        }
    </script>
</body>
</html>