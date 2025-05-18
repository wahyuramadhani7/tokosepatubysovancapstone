<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-gradient {
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            transition: all 0.3s;
        }
        .btn-gradient:hover {
            background: linear-gradient(to right, #4338CA, #6D28D9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        
        .bg-gradient-header {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F3F4F6;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c4b5fd;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a78bfa;
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
                            purple: {
                                50: '#F5F3FF',
                                100: '#EDE9FE',
                                200: '#DDD6FE',
                                300: '#C4B5FD',
                                400: '#A78BFA',
                                500: '#8B5CF6',
                                600: '#7C3AED',
                                700: '#6D28D9',
                                800: '#5B21B6',
                                900: '#4C1D95',
                            },
                            indigo: {
                                50: '#EEF2FF',
                                100: '#E0E7FF',
                                200: '#C7D2FE',
                                300: '#A5B4FC',
                                400: '#818CF8',
                                500: '#6366F1',
                                600: '#4F46E5',
                                700: '#4338CA',
                                800: '#3730A3',
                                900: '#312E81',
                            },
                            pink: {
                                50: '#FDF2F8',
                                100: '#FCE7F3',
                                200: '#FBCFE8',
                                300: '#F9A8D4',
                                400: '#F472B6',
                                500: '#EC4899',
                                600: '#DB2777',
                                700: '#BE185D',
                                800: '#9D174D',
                                900: '#831843',
                            }
                        }
                    },
                    boxShadow: {
                        'colored': '0 10px 15px -3px rgba(79, 70, 229, 0.2), 0 4px 6px -2px rgba(79, 70, 229, 0.1)',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()">
    <!-- Header/Navigation -->
    <header class="bg-gradient-header text-white shadow-md">
        <div class="container mx-auto px-4 py-5 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white rounded-full p-2.5 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-brand-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-2xl tracking-tight">Sepatu by Sovan</h1>
                    <p class="text-xs text-brand-purple-100">Premium Footwear Collection</p>
                </div>
            </div>
            <div>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg btn-gradient text-white font-medium shadow-colored">
                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md fade-in flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-1 mr-3">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" target="_blank" class="text-sm text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center font-medium transition-all shadow-md">
                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </a>
            @endif
            <button @click="dismissAlert" class="text-green-700 hover:text-green-900">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-lg mb-6 p-6 border border-purple-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-3xl font-bold text-brand-purple-900 mb-1 flex items-center">
                        <svg class="h-8 w-8 mr-3 text-brand-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Daftar Transaksi
                    </h1>
                    <p class="text-brand-purple-500">Kelola dan pantau semua transaksi penjualan</p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
                    <a href="{{ route('transactions.report') }}" target="_blank" class="px-4 py-2.5 bg-white border border-brand-purple-200 rounded-lg text-brand-purple-700 hover:bg-brand-purple-50 shadow-md transition-all flex items-center font-medium">
                        <svg class="h-5 w-5 mr-2 text-brand-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan
                    </a>
                    <a href="{{ route('transactions.create') }}" class="px-4 py-2.5 rounded-lg btn-gradient text-white flex items-center font-medium shadow-colored">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="bg-white rounded-xl shadow-lg mb-6 border border-purple-100 overflow-hidden card-hover">
            <div class="px-6 py-4 flex justify-between items-center border-b border-brand-purple-100">
                <h2 class="text-lg font-semibold text-brand-purple-800 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-brand-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-brand-purple-500 hover:text-brand-purple-700 p-1 rounded-full hover:bg-brand-purple-50 transition-colors">
                    <svg x-show="showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-6 bg-gradient-to-br from-brand-purple-50 to-white" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-brand-purple-700 mb-1">Tanggal</label>
                        <input type="date" x-model="filterDate" @change="filterTransactions" class="w-full border border-brand-purple-200 rounded-lg px-3 py-3 text-sm focus:ring-2 focus:ring-brand-purple-500 focus:border-brand-purple-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-purple-700 mb-1">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="filterTransactions" class="w-full border border-brand-purple-200 rounded-lg px-3 py-3 text-sm focus:ring-2 focus:ring-brand-purple-500 focus:border-brand-purple-500 shadow-sm">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-purple-700 mb-1">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="filterTransactions" class="w-full border border-brand-purple-200 rounded-lg px-3 py-3 text-sm focus:ring-2 focus:ring-brand-purple-500 focus:border-brand-purple-500 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button @click="resetFilters" class="px-4 py-3 bg-white border border-brand-purple-200 rounded-lg text-brand-purple-700 hover:bg-brand-purple-50 transition-colors flex items-center justify-center text-sm font-medium shadow-sm">
                        <svg class="h-5 w-5 mr-2 text-brand-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-purple-100">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-purple-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-brand-indigo-50 to-brand-purple-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider cursor-pointer" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID 
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider cursor-pointer" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Tanggal
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider cursor-pointer" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-brand-purple-800 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-brand-purple-800 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-purple-100" x-show="filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-purple-50': isNewTransaction(transaction.id)}" class="hover:bg-brand-indigo-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-brand-purple-800 bg-brand-purple-100 px-2 py-1 rounded-lg" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-brand-purple-700" x-text="formatDate(transaction.created_at || transaction.date)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-brand-purple-800" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-xs text-brand-purple-500" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-100 text-green-800 border border-green-200': transaction.payment_method === 'cash',
                                        'bg-brand-indigo-100 text-brand-indigo-800 border border-brand-indigo-200': transaction.payment_method === 'credit_card',
                                        'bg-blue-100 text-blue-800 border border-blue-200': transaction.payment_method === 'transfer'
                                    }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-brand-purple-800" x-text="formatRupiah(transaction.final_amount || transaction.total)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-100 text-green-800 border border-green-200': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                        'bg-yellow-100 text-yellow-800 border border-yellow-200': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                        'bg-red-100 text-red-800 border border-red-200': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')"></span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a :href="'{{ url('/transactions') }}/' + transaction.id + '/print'" target="_blank" class="inline-flex items-center p-2 bg-brand-indigo-100 text-brand-indigo-700 rounded-lg hover:bg-brand-indigo-200 transition-colors">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    <button @click="confirmDelete(transaction)" class="inline-flex items-center p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
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
                <div x-show="filteredTransactions.length === 0" class="text-center py-16">
                    <div class="bg-brand-purple-50 rounded-full h-24 w-24 flex items-center justify-center mx-auto mb-5 border-2 border-dashed border-brand-purple-200">
                        <svg class="h-12 w-12 text-brand-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-purple-800 mb-2">Tidak ada transaksi</h3>
                    <p class="text-brand-purple-500 max-w-md mx-auto">Belum ada transaksi yang tersedia atau sesuai dengan filter yang Anda tentukan</p>
                    <div class="mt-6">
                        <button @click="resetFilters" class="btn-gradient text-white px-5 py-2 rounded-lg shadow-colored flex items-center mx-auto">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="filteredTransactions.length > 0" class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 bg-brand-purple-50 border-t border-brand-purple-100">
                <div class="text-sm text-brand-purple-600 mb-4 sm:mb-0">
                    Menampilkan <span class="font-semibold text-brand-purple-800" x-text="paginationFrom()"></span> - <span class="font-semibold text-brand-purple-800" x-text="paginationTo()"></span> dari <span class="font-semibold text-brand-purple-800" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center space-x-2">
                    <div class="mr-2">
                        <select x-model.number="perPage" class="border border-brand-purple-200 rounded-lg p-2 text-sm shadow-sm focus:ring-2 focus:ring-brand-purple-500 focus:border-brand-purple-500">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                    <button @click="prevPage" :disabled="currentPage === 1" class="p-2 border border-brand-purple-200 rounded-lg text-sm bg-white shadow-sm" :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-brand-purple-50 hover:border-brand-purple-300': currentPage !== 1}">
                        <svg class="h-5 w-5 text-brand-purple-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <template x-for="page in displayedPages()" :key="page">
                        <button x-show="page !== '...'" @click="goToPage(page)" class="px-3 py-2 border text-sm rounded-lg shadow-sm" :class="page === currentPage ? 'bg-brand-purple-600 text-white border-brand-purple-600' : 'bg-white text-brand-purple-700 border-brand-purple-200 hover:bg-brand-purple-50'">
                            <span x-text="page"></span>
                        </button>
                        <span x-show="page === '...'" class="px-2 py-2 text-brand-purple-500">...</span>
                    </template>
                    <button @click="nextPage" :disabled="currentPage >= totalPages" class="p-2 border border-brand-purple-200 rounded-lg text-sm bg-white shadow-sm" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages, 'hover:bg-brand-purple-50 hover:border-brand-purple-300': currentPage < totalPages}">
                        <svg class="h-5 w-5 text-brand-purple-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50" x-cloak>
        <div @click.away="showDeleteModal = false" class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl transform transition-all border border-brand-purple-100 scale-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center mb-5">
                <div class="bg-red-100 rounded-full p-3 mr-4 flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 mt-1">Yakin ingin menghapus transaksi <span class="font-semibold text-brand-purple-600" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>?</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm p-3 bg-gray-50 rounded-lg mb-5 border border-gray-100">
                Tindakan ini tidak dapat dibatalkan dan semua data terkait transaksi ini akan dihapus secara permanen.
            </p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-all font-medium">
                    Batal
                </button>
                <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all font-medium shadow-md">
                        Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <div x-show="newTransactionId" class="fixed bottom-6 right-6" x-cloak x-transition>
        <a :href="'{{ url('/transactions') }}/' + newTransactionId + '/print'" target="_blank" class="flex items-center justify-center h-14 w-14 bg-brand-indigo-600 text-white rounded-full shadow-xl hover:bg-brand-indigo-700 transition-all transform hover:scale-105">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    document.querySelector('.fade-in')?.remove();
                },

                isNewTransaction(id) {
                    return id == this.newTransactionId;
                },

                highlightNewTransaction() {
                    const index = this.transactions.findIndex(t => t.id == this.newTransactionId);
                    if (index >= 0) {
                        this.goToPage(Math.floor(index / this.perPage) + 1);
                        this.$nextTick(() => {
                            document.querySelector(`tr.bg-brand-purple-50`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
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