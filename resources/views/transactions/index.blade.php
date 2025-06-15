<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;700" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }

        /* Futuristic background with dynamic gradient */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #1a202c 100%), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            background-blend-mode: overlay;
            color: #e2e8f0;
            min-height: 100vh;
            padding-top: 5rem;
            position: relative;
        }

        /* Dark overlay for contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 32, 44, 0.7);
            z-index: -1;
        }

        /* Smooth hover animation */
        .hover-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 209, 197, 0.3);
            transition: all 0.3s ease;
        }

        /* Futuristic button style */
        .btn-futuristic {
            background: linear-gradient(90deg, #4fd1c5, #81e6d9);
            color: #1a202c;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 8px;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-futuristic:hover {
            background-position: 100%;
            border-color: #4fd1c5;
            transform: translateY(-2px);
        }
        .btn-futuristic::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .btn-futuristic:hover::after {
            left: 100%;
        }

        /* Header with sleek gradient */
        .bg-futuristic-header {
            background: linear-gradient(180deg, rgba(26, 32, 44, 0.95), rgba(79, 209, 197, 0.1));
            border-bottom: 1px solid #4fd1c5;
        }

        /* Card with neon accent */
        .card-futuristic {
            background: rgba(45, 55, 72, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(79, 209, 197, 0.2);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card-futuristic:hover {
            transform: translateY(-3px);
            border-color: rgba(79, 209, 197, 0.4);
        }

        /* Status badges with neon colors */
        .status-badge {
            padding: 0.4rem 1.2rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .status-badge.bg-teal-900\/50 {
            background: rgba(79, 209, 197, 0.5);
            color: #e6fffa;
            border-color: rgba(79, 209, 197, 0.2);
        }
        .status-badge.bg-yellow-900\/50 {
            background: rgba(236, 201, 75, 0.5);
            color: #fefcbf;
            border-color: rgba(236, 201, 75, 0.2);
        }
        .status-badge.bg-red-900\/50 {
            background: rgba(245, 101, 101, 0.5);
            color: #fed7d7;
            border-color: rgba(245, 101, 101, 0.2);
        }
        .status-badge.bg-gray-900\/50 {
            background: rgba(113, 128, 150, 0.5);
            color: #e2e8f0;
            border-color: rgba(113, 128, 150, 0.2);
        }
        .status-badge.bg-blue-900\/50 {
            background: rgba(66, 153, 225, 0.5);
            color: #bee3f8;
            border-color: rgba(66, 153, 225, 0.2);
        }

        /* Custom scrollbar with neon theme */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(45, 55, 72, 0.5);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4fd1c5;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #81e6d9;
        }

        /* Inputs with sleek design */
        input, select {
            background: rgba(45, 55, 72, 0.8);
            border: 1px solid rgba(79, 209, 197, 0.3);
            color: #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #4fd1c5;
            box-shadow: 0 0 5px rgba(79, 209, 197, 0.5);
        }

        /* Table row hover effect */
        tbody tr:hover {
            background: rgba(79, 209, 197, 0.1) !important;
        }

        /* Slide-in animation */
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
                                teal: '#4fd1c5',
                                light: '#e6fffa',
                            },
                            dark: {
                                50: '#f7fafc',
                                100: '#edf2f7',
                                200: '#e2e8f0',
                                300: '#cbd5e0',
                                400: '#a0aec0',
                                500: '#718096',
                                600: '#4a5568',
                                700: '#2d3748',
                                800: '#1a202c',
                                900: '#171923',
                            },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()">
    <!-- Header/Navigation -->
    <header class="fixed top-0 w-full bg-futuristic-header text-white z-50">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-brand-neon-teal rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Orbitron'] font-bold text-xl tracking-tight text-brand-neon-light">Sepatu by Sovan</h1>
                    <p class="text-xs text-gray-400 font-light">Luxury Footwear Collection</p>
                </div>
            </div>
            <div>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="inline-flex items-center p-2 bg-brand-dark-700 rounded-full text-brand-neon-light hover-glow" title="Dashboard">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12 max-w-7xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-brand-dark-800 border border-brand-neon-teal/20 text-white p-4 rounded-lg slide-in flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <div class="bg-brand-neon-teal rounded-full p-1.5 mr-2">
                    <svg class="h-5 w-5 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <button @click="printReceipt({ id: newTransactionId })" class="text-sm text-brand-dark-900 bg-brand-neon-teal hover:bg-brand-neon-light px-4 py-1.5 rounded-lg flex items-center font-medium hover-glow">
                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </button>
            @endif
            <button @click="dismissAlert" class="text-brand-neon-light hover:text-brand-neon-teal p-1.5 rounded-full hover:bg-brand-dark-700">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="card-futuristic rounded-lg mb-6 p-6 border slide-in">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="mb-4 lg:mb-0">
                    <h1 class="font-['Orbitron'] text-3xl font-bold text-white mb-1 flex items-center">
                        <svg class="h-8 w-8 mr-2 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Sistem Kasir
                    </h1>
                    <p class="text-gray-400 text-sm">Kelola transaksi dengan antarmuka futuristik dan efisien</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('transactions.create') }}" class="px-4 py-2 rounded-lg btn-futuristic text-sm flex items-center hover-glow">
                        <svg class="h-4 w-4 mr-1.5 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card-futuristic rounded-lg mb-6 border overflow-hidden slide-in">
            <div class="px-5 py-4 flex justify-between items-center border-b border-brand-neon-teal/10">
                <h2 class="text-base font-medium text-white flex items-center">
                    <svg class="h-4 w-4 mr-1.5 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-400 hover:text-brand-neon-teal p-1 rounded-full hover:bg-brand-dark-700">
                    <svg x-show="showFilters" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-5 bg-brand-dark-800/30" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Tanggal</label>
                        <input type="date" x-model="filterDate" @change="filterTransactions" class="w-full rounded-lg px-3 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="filterTransactions" class="w-full rounded-lg px-3 py-1.5 text-sm">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="filterTransactions" class="w-full rounded-lg px-3 py-1.5 text-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="resetFilters" class="px-4 py-1.5 bg-brand-dark-700 border border-brand-neon-teal/20 rounded-lg text-gray-400 hover:bg-brand-dark-600 text-sm flex items-center hover-glow">
                        <svg class="h-3 w-3 mr-1 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24  sonor:currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card-futuristic rounded-lg overflow-hidden border slide-in">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-neon-teal/10">
                    <thead>
                        <tr class="bg-brand-dark-800">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Tanggal
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Metode</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider cursor-pointer" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-neon-teal/10" x-show="filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-dark-700/30': isNewTransaction(transaction.id)}" class="transition-colors duration-200">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-white bg-brand-dark-600 px-2 py-0.5 rounded-lg" x-text="transaction.invoice_number || ('TRX-' + transaction.id.toString().padStart(6, '0'))"></span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="text-sm text-gray-400" x-text="formatDate(transaction.created_at || transaction.date)"></span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-xs text-gray-500" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-teal-900/50 text-teal-300 border-teal-200/20': transaction.payment_method === 'cash',
                                        'bg-gray-900/50 text-gray-300 border-gray-200/20': transaction.payment_method === 'credit_card',
                                        'bg-blue-900/50 text-blue-300 border-blue-200/20': transaction.payment_method === 'transfer'
                                    }" x-text="translatePaymentMethod(transaction.payment_method)"></span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-brand-neon-teal" x-text="formatRupiah(transaction.final_amount || transaction.total)"></span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="status-badge" :class="{
                                        'bg-teal-900/50 text-teal-300 border-teal-200/20': transaction.payment_status === 'paid' || transaction.status === 'completed',
                                        'bg-yellow-900/50 text-yellow-300 border-yellow-200/20': transaction.payment_status === 'pending' || transaction.status === 'pending',
                                        'bg-red-900/50 text-red-300 border-red-200/20': transaction.payment_status === 'cancelled' || transaction.status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status || transaction.status || 'paid')"></span>
                                </td>
                                <td class="px-5 py-3 text-right space-x-1 whitespace-nowrap">
                                    <button @click="printReceipt(transaction)" class="inline-flex items-center p-1.5 bg-brand-dark-600 text-gray-400 rounded-lg hover:bg-brand-dark-500 hover-glow">
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(transaction)" class="inline-flex items-center p-1.5 bg-red-900/30 text-red-400 rounded-lg hover:bg-red-800/30 hover-glow">
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div x-show="filteredTransactions.length === 0" class="text-center py-16">
                    <div class="bg-brand-dark-700 rounded-full h-3 p-2 border-white border-radius-10">
                        <svg class="h-10 w-10 text-brand-neon-teal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="font-['Orbitron'] text-xl font-bold text-white mb-2">Tidak Ada Transaksi</h3>
                    <p class="text-gray-400 max-w-sm mx-auto text-sm">Belum ada transaksi yang tersedia atau sesuai dengan filter yang Anda tetapkan.</p>
                    <div class="mt-4">
                        <button @click="resetFilters" class="btn-futuristic text-blue-900-dark-900 px-4 py-1.5 rounded-lg text-sm flex items-center mx-auto hover-glow">
                            <svg class="h-3 w-3 mr-1 text-brand-dark-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="filteredTransactions.length > 0" class="flex flex-col md:flex-row items-center justify-between px-5 py-4 bg-brand-dark-800/30 border-t border-brand-neon-teal/10">
                <div class="text-xs text-gray-400 mb-2 md:mb-0">
                    Menampilkan <span class="font-medium text-white" x-text="paginationFrom()"></span> - <span class="font-medium text-white" x-text="paginationTo()"></span> dari <span class="font-medium text-white" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center space-x-1">
                    <div class="mr-1">
                        <select x-model.number="perPage" class="border border-brand-neon-teal/20 bg-brand-dark-700 text-white rounded-lg p-1.5 text-xs">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                    <button @click="prevPage" :disabled="currentPage === 1" class="p-1.5 border border-brand-neon-teal/20 rounded-lg text-xs bg-brand-dark-700" :class="{'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-brand-dark-600 hover:border-brand-neon-teal': currentPage !== 1}">
                        <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <template x-for="page in displayedPages()" :key="page">
                        <button x-show="page !== '...'" @click="goToPage(page)" class="px-2.5 py-1 border text-xs rounded-lg" :class="page === currentPage ? 'bg-brand-neon-teal text-brand-dark-900 border-brand-neon-teal' : 'bg-brand-dark-700 text-gray-400 border-brand-neon-teal/20 hover:bg-brand-dark-600'">
                            <span x-text="page"></span>
                        </button>
                        <span x-show="page === '...'" class="px-2 py-1 text-gray-500">...</span>
                    </template>
                    <button @click="nextPage" :disabled="currentPage >= totalPages" class="p-1.5 border border-brand-neon-teal/20 rounded-lg text-xs bg-brand-dark-700" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages, 'hover:bg-brand-dark-600 hover:border-brand-neon-teal': currentPage < totalPages}">
                        <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" x-cloak>
        <div @click.away="showDeleteModal = false" class="card-futuristic rounded-lg max-w-md w-full p-5 border slide-in">
            <div class="flex items-center mb-4">
                <div class="bg-red-900/30 rounded-full p-2 mr-2">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-['Orbitron'] text-lg font-bold text-white">Konfirmasi Hapus</h3>
                    <p class="text-gray-400 text-sm">Yakin ingin menghapus transaksi <span class="font-medium text-brand-neon-teal" x-text="transactionToDelete ? (transactionToDelete.invoice_number || ('TRX-' + transactionToDelete.id.toString().padStart(6, '0'))) : ''"></span>?</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm p-2 bg-brand-dark-700/30 rounded-lg mb-4 border border-brand-neon-teal/10">
                Tindakan ini tidak dapat dibatalkan dan semua data terkait transaksi ini akan dihapus permanen.
            </p>
            <div class="flex justify-end space-x-2">
                <button @click="showDeleteModal = false" class="px-4 py-1.5 bg-brand-dark-700 hover:bg-brand-dark-600 text-gray-400 rounded-lg text-sm hover-glow">
                    Batal
                </button>
                <form :action="'{{ url('/transactions') }}/' + (transactionToDelete ? transactionToDelete.id : '')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-1.5 bg-red-900/30 hover:bg-red-800/30 text-red-400 rounded-lg text-sm hover-glow">
                        Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <div x-show="newTransactionId" class="fixed bottom-6 right-6" x-cloak>
        <button @click="printReceipt({ id: newTransactionId })" class="flex items-center justify-center h-12 w-12 bg-brand-neon-teal text-brand-dark-900 rounded-full hover:bg-brand-neon-light hover-glow">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
        </button>
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

                printReceipt(transaction) {
                    try {
                        const printUrl = `{{ route('transactions.print', ':id') }}`.replace(':id', transaction.id);
                        const printWindow = window.open(printUrl, '_blank');
                        if (!printWindow) {
                            alert('Gagal membuka halaman cetak. Pastikan popup tidak diblokir oleh browser.');
                        }
                    } catch (e) {
                        console.error('Error membuka halaman cetak:', e);
                        alert('Terjadi kesalahan saat mencoba mencetak struk.');
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
                            document.querySelector(`tr.bg-brand-dark-700/30`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
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