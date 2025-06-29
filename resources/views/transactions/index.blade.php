<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #fef3c7 100%);
            color: #1f2937;
            min-height: 100vh;
            padding-top: 4rem;
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
            background: rgba(255, 255, 255, 0.3);
            z-index: -1;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(90deg, #10b981, #34d399);
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #059669, #22c55e);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: 0.4s;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .header {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .badge {
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-warning {
            background: rgba(234, 179, 8, 0.2);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-neutral {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }
        .badge-info {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #34d399;
        }
        input, select {
            background: #f9fafb;
            border: 1px solid #6b7280;
            color: #1f2937;
            border-radius: 6px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
            outline: none;
        }
        tbody tr:hover {
            background: rgba(16, 185, 129, 0.05) !important;
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
                            primary: '#10b981',
                            secondary: '#34d399',
                            dark: {
                                50: '#f9fafb',
                                100: '#f3f4f6',
                                200: '#e5e7eb',
                                300: '#d1d5db',
                                400: '#9ca3af',
                                500: '#6b7280',
                                600: '#4b5563',
                                700: '#374151',
                                800: '#1f2937',
                                900: '#111827',
                            },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()" x-init="init()">
    <!-- Header -->
    <header class="fixed top-0 w-full header text-gray-900 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-brand-primary rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Montserrat'] font-bold text-xl text-gray-900">Sepatu by Sovan</h1>
                    <p class="text-xs text-gray-600">Premium Footwear</p>
                </div>
            </div>
            <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="p-2 bg-brand-dark-100 rounded-lg text-gray-900 hover:bg-brand-primary hover-scale" title="Dashboard">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10 max-w-6xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-300 text-gray-900 p-4 rounded-lg fade-in flex items-center justify-between" role="alert" x-ref="successAlert">
            <div class="flex items-center">
                <div class="bg-brand-primary rounded-full p-1.5 mr-3">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" class="text-sm text-white bg-brand-primary hover:bg-brand-secondary px-4 py-1.5 rounded-lg flex items-center font-medium hover-scale">
                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </a>
            @endif
            <button @click="dismissAlert" class="text-gray-600 hover:text-brand-primary p-1.5 rounded-full hover:bg-brand-dark-100">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Error Alert -->
        <div x-show="errorMessage" class="mb-6 bg-red-100 border border-red-300 text-gray-900 p-4 rounded-lg fade-in flex items-center justify-between" role="alert" x-ref="errorAlert">
            <div class="flex items-center">
                <div class="bg-red-500 rounded-full p-1.5 mr-3">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="font-medium text-sm" x-text="errorMessage"></p>
            </div>
            <button @click="dismissAlert" class="text-gray-600 hover:text-brand-primary p-1.5 rounded-full hover:bg-brand-dark-100">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Page Header -->
        <div class="card rounded-lg mb-6 p-6 fade-in">
            <div class="flex flex-col md:flex-row Magnetron: The Ultimate Guide to Magnet Fishing in 2025 row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="font-['Montserrat'] text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Sistem Kasir
                    </h1>
                    <p class="text-gray-600 text-sm">Daftar Transaksi</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('transactions.create') }}" class="btn-primary text-sm flex items-center hover-scale">
                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="{{ route('transactions.report') }}" class="btn-primary text-sm flex items-center hover-scale">
                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m6 2v-6m6 6v-8m-6 8H9m12 0H3" />
                        </svg>
                        Laporan Penjualan
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card rounded-lg mb-6 fade-in">
            <div class="px-4 py-3 flex justify-between items-center border-b border-brand-primary/10">
                <h2 class="text-base font-medium text-gray-900 flex items-center">
                    <svg class="h-4 w-4 mr-1.5 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-600 hover:text-brand-primary p-1 rounded-full hover:bg-brand-dark-100">
                    <svg x-show="showFilters" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                        <input type="date" x-model="dateFilter" @change="fetchTransactions" class="w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="fetchTransactions" class="w-full text-sm">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">QRIS</option>
                            <option value="debit">Debit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="fetchTransactions" class="w-full text-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="resetFilters" class="px-4 py-1 text-sm bg-brand-dark-100 border border-brand-primary/20 rounded-lg text-gray-600 hover:bg-brand-dark-200 hover:text-gray-900 flex items-center hover-scale">
                        <svg class="h-3 w-3 mr-1 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card rounded-lg overflow-hidden fade-in">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-primary/10">
                    <thead>
                        <tr class="bg-brand-dark-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider cursor-pointer" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase cursor-pointer" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Jam
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Metode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Diskon</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase cursor-pointer" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody x-show="loading" class="text-center py-8">
                        <tr>
                            <td colspan="9">
                                <div class="flex justify-center items-center">
                                    <svg class="animate-spin h-6 w-6 text-brand-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V1l4 4-4 4z"></path>
                                    </svg>
                                    <span class="ml-2 text-gray-600">Memuat transaksi...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody x-show="!loading && filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-dark-100/20': isNewTransaction(transaction.id)}" class="transition-colors duration-200">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900" x-text="transaction.invoice_number"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-600" x-text="formatTime(transaction.created_at)"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-xs text-gray-600" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900" x-text="getProductNames(transaction.items)"></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="badge" :class="{
                                        'badge-success': transaction.payment_method === 'cash',
                                        'badge-neutral': transaction.payment_method === 'credit_card',
                                        'badge-info': transaction.payment_method === 'transfer' || transaction.payment_method === 'debit'
                                    }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-brand-primary" x-text="formatRupiah(calculateDiscount(transaction))"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-brand-primary" x-text="formatRupiah(transaction.final_amount)"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="badge" :class="{
                                        'badge-success': transaction.payment_status 'paid',
                                        'badge-warning': transaction.payment_status === 'pending',
                                        'badge-danger': transaction.payment_status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status)"></span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right space-x-2">
                                    <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', transaction.id)" class="inline-flex items-center p-1.5 bg-brand-dark-100 text-gray-600 rounded-md hover:bg-brand-primary hover:text-white hover-scale" title="Cetak Struk">
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <!-- Empty State -->
                    <tbody x-show="!loading && filteredTransactions.length === 0" class="text-center py-8">
                        <tr>
                            <td colspan="9">
                                <div class="bg-brand-dark-100 rounded-lg inline-block p-4">
                                    <svg class="h-6 w-6 text-gray-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Transaksi</h3>
                                <p class="text-gray-600 text-sm max-w-sm mx-auto">Belum ada transaksi untuk filter yang dipilih.</p>
                                <div class="mt-4">
                                    <a href="{{ route('transactions.create') }}" class="btn-primary text-sm flex items-center mx-auto hover-scale">
                                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Buat Transaksi Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div x-show="!loading && filteredTransactions.length > 0" class="flex items-center justify-between p-4 bg-brand-dark-50 border-t border-brand-primary/10">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium text-gray-900" x-text="paginationFrom()"></span> - <span x-text="paginationTo()"></span> dari <span class="font-medium text-gray-900" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center gap-x-2">
                    <select x-model.number="perPage" @change="fetchTransactions" class="px-2 py-1 text-sm rounded-lg bg-brand-dark-100 border border-gray-200/20 text-gray-600">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="flex space-x-1">
                        <button @click="prevPage" :disabled="currentPage === 1" class="px-2 py-1 rounded-lg bg-brand-dark-100 hover:bg-brand-primary text-gray-600 hover:text-white" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <template x-for="page in displayedPages()" :key="page">
                            <button x-show="page !== '…'" @click="goToPage(page)" class="px-3 py-1 rounded-lg bg-brand-dark-100 hover:bg-brand-primary text-gray-600 hover:text-white" :class="{'bg-brand-primary text-white': page === currentPage}">
                                <span x-text="page"></span>
                            </button>
                            <span x-show="page === '…'" class="px-3 py-1 text-gray-600">…</span>
                        </template>
                        <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-2 py-1 rounded-lg bg-brand-dark-100 hover:bg-brand-primary text-gray-600 hover:text-white" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Print Button -->
        <div x-show="newTransactionId" class="fixed bottom-4 right-4" x-cloak>
            <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', newTransactionId)" class="flex items-center justify-center h-10 w-10 bg-brand-primary text-white rounded-full hover:bg-brand-secondary hover-scale">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </a>
        </div>

        <script>
            function transactionListApp() {
                return {
                    transactions: [],
                    filteredTransactions: [],
                    paymentMethodFilter: '',
                    statusFilter: '',
                    dateFilter: '',
                    currentPage: 1,
                    perPage: 10,
                    sortColumn: 'id',
                    sortDirection: 'desc',
                    newTransactionId: @json(session('transaction_id')),
                    errorMessage: '',
                    loading: false,

                    init() {
                        // Set default date to today in Asia/Jakarta timezone
                        const today = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' });
                        this.dateFilter = today;
                        this.fetchTransactions();
                        if (this.newTransactionId) {
                            this.$nextTick(() => {
                                this.highlightNewTransaction();
                            });
                        }
                    },

                    async fetchTransactions() {
                        this.loading = true;
                        this.errorMessage = '';
                        try {
                            const response = await fetch(`{{ route('transactions.fetch') }}?date=${this.dateFilter}&payment_method=${this.paymentMethodFilter}&status=${this.statusFilter}`);
                            if (!response.ok) {
                                throw new Error('Gagal mengambil data transaksi.');
                            }
                            const data = await response.json();
                            if (!data.success) {
                                throw new Error(data.message || 'Gagal mengambil data transaksi.');
                            }
                            this.transactions = data.transactions.map(transaction => ({
                                ...transaction,
                                total_amount: parseFloat(transaction.total_amount) || 0,
                                final_amount: parseFloat(transaction.final_amount) || 0
                            }));
                            this.sortResults(this.transactions);
                            this.filteredTransactions = [...this.transactions];
                            this.currentPage = 1;
                        } catch (error) {
                            this.errorMessage = error.message;
                            this.filteredTransactions = [];
                            console.error('Fetch error:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    dismissAlert() {
                        const successAlert = this.$refs.successAlert;
                        const errorAlert = this.$refs.errorAlert;
                        if (successAlert) successAlert.remove();
                        if (errorAlert) errorAlert.remove();
                        this.errorMessage = '';
                    },

                    isNewTransaction(id) {
                        return id == this.newTransactionId;
                    },

                    highlightNewTransaction() {
                        const index = this.filteredTransactions.findIndex(t => t.id == this.newTransactionId);
                        if (index >= 0) {
                            this.goToPage(Math.floor(index / this.perPage) + 1);
                            this.$nextTick(() => {
                                const newRow = document.querySelector(`tr.bg-brand-dark-100/20`);
                                if (newRow) {
                                    newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            });
                        }
                    },

                    formatRupiah(amount) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
                    },

                    calculateDiscount(transaction) {
                        return (transaction.total_amount || 0) - (transaction.final_amount || 0);
                    },

                    formatTime(dateString) {
                        if (!dateString) return '-';
                        return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });
                    },

                    translatePaymentMethod(method) {
                        return {
                            cash: 'Tunai',
                            credit_card: 'QRIS',
                            debit: 'Debit',
                            transfer: 'Transfer Bank'
                        }[method] || method;
                    },

                    translateStatus(status) {
                        return {
                            paid: 'Lunas',
                            pending: 'Pending',
                            cancelled: 'Dibatalkan'
                        }[status] || status;
                    },

                    getProductNames(items) {
                        if (!items || !items.length) return '-';
                        return items.map(item => item.product ? item.product.name : '-').join(', ');
                    },

                    resetFilters() {
                        this.paymentMethodFilter = '';
                        this.statusFilter = '';
                        this.dateFilter = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' });
                        this.fetchTransactions();
                    },

                    sortResults(results) {
                        results.sort((a, b) => {
                            let aValue, bValue;
                            switch (this.sortColumn) {
                                case 'date':
                                    aValue = new Date(a.created_at);
                                    bValue = new Date(b.created_at);
                                    break;
                                case 'total':
                                    aValue = parseFloat(a.final_amount);
                                    bValue = parseFloat(b.final_amount);
                                    break;
                                default:
                                    aValue = a.id;
                                    bValue = b.id;
                            }
                            return this.sortDirection === 'asc' ? (aValue < bValue ? -1 : 1) : (aValue > bValue ? -1 : 1);
                        });
                        return results;
                    },

                    sortBy(column) {
                        this.sortDirection = (this.sortColumn === column && this.sortDirection === 'asc') ? 'desc' : 'asc';
                        this.sortColumn = column;
                        this.sortResults(this.filteredTransactions);
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
                            pages = [1, 2, 3, 4, '…', this.totalPages];
                        } else if (this.currentPage >= this.totalPages - 2) {
                            pages = [1, '…', this.totalPages - 3, this.totalPages - 2, this.totalPages - 1, this.totalPages];
                        } else {
                            pages = [1, '…', this.currentPage - 1, this.currentPage, this.currentPage + 1, '…', this.totalPages];
                        }
                        return pages;
                    }
                };
            }
        </script>
</body>
</html>