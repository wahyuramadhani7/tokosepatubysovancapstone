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
        input, select {
            background: rgba(20, 20, 40, 0.8);
            border: 1px solid rgba(255, 0, 204, 0.3);
            color: #E0E0E0;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #ff00cc;
            box-shadow: 0 0 12px rgba(255, 0, 204, 0.5);
        }

        /* Table row hover with neon effect */
        tbody tr {
            transition: all 0.3s ease;
        }
        tbody tr:hover {
            background: rgba(255, 0, 204, 0.1) !important;
            box-shadow: 0 0 15px rgba(255, 0, 204, 0.2);
        }

        /* Slide-in animation */
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
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
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()">
    <!-- Header/Navigation -->
    <header class="fixed top-0 w-full bg-neon-header text-white shadow-lg z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-brand-neon-pink rounded-full p-2.5 shadow-neon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Playfair_Display'] font-bold text-2xl tracking-tight text-brand-neon-pink">Sepatu by Sovan</h1>
                    <p class="text-xs text-gray-400 font-light">Luxury Footwear Collection</p>
                </div>
            </div>
            <div>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl btn-neon font-medium shadow-neon hover:shadow-neon pulse-hover">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-16 max-w-7xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-8 bg-brand-dark-800 border border-brand-neon-pink/30 text-white p-5 rounded-xl shadow-neon slide-in flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <div class="bg-brand-neon-pink rounded-full p-2 mr-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" target="_blank" class="text-sm text-white bg-brand-neon-pink hover:bg-brand-neon-blue px-5 py-2 rounded-xl flex items-center font-medium transition-all shadow-neon pulse-hover">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Invoice
            </a>
            @endif
            <button @click="dismissAlert" class="text-brand-neon-pink hover:text-brand-neon-blue p-2 rounded-full hover:bg-brand-dark-700 transition-colors">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="card-neon rounded-xl shadow-neon mb-8 p-8 border border-brand-neon-pink/20 slide-in">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-6 lg:mb-0">
                    <h1 class="font-['Playfair_Display'] text-4xl font-bold text-white mb-2 flex items-center">
                        <svg class="h-10 w-10 mr-3 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Sistem Kasir
                    </h1>
                    <p class="text-gray-400 text-base">Pantau dan kelola transaksi dengan desain futuristik dan interaktif</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('transactions.report') }}" target="_blank" class="px-5 py-2.5 bg-brand-dark-800 border border-brand-neon-pink/30 rounded-xl text-gray-300 hover:bg-brand-dark-700 shadow-neon transition-all flex items-center font-medium text-sm pulse-hover">
                        <svg class="h-5 w-5 mr-2 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan
                    </a>
                    <a href="{{ route('transactions.create') }}" class="px-5 py-2.5 rounded-xl btn-neon text-white flex items-center font-medium text-sm shadow-neon pulse-hover">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card-neon rounded-xl shadow-neon mb-8 border border-brand-neon-pink/20 overflow-hidden slide-in">
            <div class="px-6 py-5 flex justify-between items-center border-b border-brand-neon-pink/10">
                <h2 class="text-lg font-medium text-white flex items-center">
                    <svg class="h-5 w-5 mr-2 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-400 hover:text-brand-neon-pink p-1.5 rounded-full hover:bg-brand-dark-700 transition-colors">
                    <svg x-show="showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-6 bg-gradient-to-br from-brand-dark-800/50 to-brand-dark-900/50" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Tanggal</label>
                        <input type="date" x-model="filterDate" @change="filterTransactions" class="w-full rounded-xl px-3 py-2 text-sm shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="filterTransactions" class="w-full rounded-xl px-3 py-2 text-sm shadow-sm">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="filterTransactions" class="w-full rounded-xl px-3 py-2 text-sm shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button @click="resetFilters" class="px-5 py-2 bg-brand-dark-800 border border-brand-neon-pink/30 rounded-xl text-gray-300 hover:bg-brand-dark-700 transition-all flex items-center justify-center text-sm font-medium shadow-neon pulse-hover">
                        <svg class="h-4 w-4 mr-1.5 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card-neon rounded-xl shadow-neon overflow-hidden border border-brand-neon-pink/20 slide-in">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-neon-pink/10">
                    <thead>
                        <tr class="bg-gradient-to-r from-brand-dark-800 to-brand-dark-900">
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1.5"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Tanggal
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1.5"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1.5"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-neon-pink/10" x-show="filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-dark-800/40': isNewTransaction(transaction.id)}" class="transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-white bg-brand-dark-700 px-2.5 py-1 rounded-xl" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-400" x-text="formatDate(transaction.created_at || transaction.date)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-xs text-gray-500" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-900/50 text-green-300 border-green-200/20': transaction.payment_method === 'cash',
                                        'bg-gray-900/50 text-gray-300 border-gray-200/20': transaction.payment_method === 'credit_card',
                                        'bg-blue-900/50 text-blue-300 border-blue-200/20': transaction.payment_method === 'transfer'
                                    }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-brand-neon-pink" x-text="formatRupiah(transaction.final_amount || transaction.total)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-green-900/50 text-green-300 border-green-200/20': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                        'bg-yellow-900/50 text-yellow-300 border-yellow-200/20': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                        'bg-red-900/50 text-red-300 border-red-200/20': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')"></span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a :href="'{{ url('/transactions') }}/' + transaction.id + '/print'" target="_blank" class="inline-flex items-center p-2 bg-brand-dark-700 text-gray-300 rounded-xl hover:bg-brand-dark-600 transition-colors pulse-hover">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    <button @click="confirmDelete(transaction)" class="inline-flex items-center p-2 bg-red-900/50 text-red-300 rounded-xl hover:bg-red-800/50 transition-colors pulse-hover">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div x-show="filteredTransactions.length === 0" class="text-center py-20">
                    <div class="bg-brand-dark-800 rounded-full h-28 w-28 flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-brand-neon-pink/30">
                        <svg class="h-14 w-14 text-brand-neon-pink" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-['Playfair_Display'] text-2xl font-bold text-white mb-3">Tidak Ada Transaksi</h3>
                    <p class="text-gray-400 max-w-md mx-auto text-base">Belum ada transaksi yang tersedia atau sesuai dengan filter yang Anda tentukan.</p>
                    <div class="mt-6">
                        <button @click="resetFilters" class="btn-neon text-white px-5 py-2 rounded-xl shadow-neon flex items-center mx-auto text-sm font-medium pulse-hover">
                            <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="filteredTransactions.length > 0" class="flex flex-col md:flex-row items-center justify-between px-6 py-5 bg-brand-dark-800/50 border-t border-brand-neon-pink/10">
                <div class="text-sm text-gray-400 mb-3 md:mb-0">
                    Menampilkan <span class="font-medium text-white" x-text="paginationFrom()"></span> - <span class="font-medium text-white" x-text="paginationTo()"></span> dari <span class="font-medium text-white" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center space-x-2">
                    <div class="mr-2">
                        <select x-model.number="perPage" class="border border-brand-neon-pink/30 bg-brand-dark-800 text-white rounded-xl p-2 text-sm shadow-neon focus:ring-2 focus:ring-brand-neon-pink">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                    <button @click="prevPage" :disabled="currentPage === 1" class="p-2 border border-brand-neon-pink/30 rounded-xl text-sm bg-brand-dark-800 shadow-neon" :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-brand-dark-700 hover:border-brand-neon-pink': currentPage !== 1}">
                        <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <template x-for="page in displayedPages()" :key="page">
                        <button x-show="page !== '...'" @click="goToPage(page)" class="px-3 py-1.5 border text-sm rounded-xl shadow-neon" :class="page === currentPage ? 'bg-brand-neon-pink text-white border-brand-neon-pink' : 'bg-brand-dark-800 text-gray-300 border-brand-neon-pink/30 hover:bg-brand-dark-700'">
                            <span x-text="page"></span>
                        </button>
                        <span x-show="page === '...'" class="px-2 py-1.5 text-gray-500">...</span>
                    </template>
                    <button @click="nextPage" :disabled="currentPage >= totalPages" class="p-2 border border-brand-neon-pink/30 rounded-xl text-sm bg-brand-dark-800 shadow-neon" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages, 'hover:bg-brand-dark-700 hover:border-brand-neon-pink': currentPage < totalPages}">
                        <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" x-cloak>
        <div @click.away="showDeleteModal = false" class="card-neon rounded-xl max-w-md w-full p-6 shadow-neon border border-brand-neon-pink/20 slide-in">
            <div class="flex items-center mb-5">
                <div class="bg-red-900/50 rounded-full p-3 mr-3 flex-shrink-0">
                    <svg class="h-6 w-6 text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-['Playfair_Display'] text-xl font-bold text-white">Konfirmasi Hapus</h3>
                    <p class="text-gray-400 mt-1 text-sm">Yakin ingin menghapus transaksi <span class="font-medium text-brand-neon-pink" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>?</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm p-3 bg-brand-dark-800/50 rounded-xl mb-5 border border-brand-neon-pink/10">
                Tindakan ini tidak dapat dibatalkan dan semua data terkait transaksi ini akan dihapus secara permanen.
            </p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false" class="px-5 py-2 bg-brand-dark-800 hover:bg-brand-dark-700 text-gray-300 rounded-xl transition-all font-medium text-sm pulse-hover">
                    Batal
                </button>
                <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-900/50 hover:bg-red-800/50 text-red-300 rounded-xl transition-all font-medium text-sm shadow-neon pulse-hover">
                        Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <div x-show="newTransactionId" class="fixed bottom-8 right-8" x-cloak>
        <a :href="'{{ url('/transactions') }}/' + newTransactionId + '/print'" target="_blank" class="flex items-center justify-center h-14 w-14 bg-brand-neon-pink text-white rounded-full shadow-neon hover:bg-brand-neon-blue transition-all duration-300 pulse-hover">
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
                    const alert = document.querySelector('.slide-in');
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
                            document.querySelector(`tr.bg-brand-dark-800/40`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
                        const selectedDateStr = selectedDate.toISOString().split('T')[0];
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