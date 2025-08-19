<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'black-custom': '#000000',
                        'orange-custom': '#FF6B35',
                        'green-custom': '#4ADE80',
                        'gray-dark': '#374151',
                        'gray-medium': '#6B7280',
                        'blue-custom': '#2C3E50',
                        'gold-custom': '#D4AF37'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('images/bgapp.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #1F2937;
            min-height: 100vh;
            padding: 1rem;
            font-size: 16px;
            position: relative;
            font-weight: 400;
            line-height: 1.5;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(243, 244, 246, 0.8);
            z-index: -1;
        }
        .dark body::before {
            background: rgba(31, 41, 55, 0.8);
        }
        .dark body {
            color: #F3F4F6;
        }
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        .btn-primary {
            background: #000000;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 1rem;
            line-height: 1.5;
        }
        .dark .btn-primary {
            background: #333333;
        }
        .btn-primary:hover {
            background: #333333;
            transform: translateY(-2px);
        }
        .dark .btn-primary:hover {
            background: #4D4D4D;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            transition: transform 0.2s ease;
        }
        .dark .card {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid #4B5563;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #E5E7EB;
            border-radius: 8px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 8px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #9CA3AF;
        }
        input, select, textarea {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            color: #1F2937;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
        }
        .dark input, .dark select, .dark textarea {
            background: #374151;
            border: 1px solid #6B7280;
            color: #F3F4F6;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #000000;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.4);
            outline: none;
        }
        .dark input:focus, .dark select:focus, .dark textarea:focus {
            border-color: #333333;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .badge-success {
            background: rgba(74, 222, 128, 0.1);
            color: #4ADE80;
            border: 1px solid rgba(74, 222, 128, 0.3);
        }
        .badge-warning {
            background: rgba(251, 191, 36, 0.1);
            color: #FBBF24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }
        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-neutral {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }
        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-height: 120px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #6B7280 #E5E7EB;
            background: rgba(249, 250, 251, 0.95);
            padding: 0.5rem;
            border-radius: 0.25rem;
            max-width: 100%;
            overflow-wrap: break-word;
            font-size: 0.875rem;
        }
        .dark .product-list {
            scrollbar-color: #9CA3AF #374151;
            background: rgba(55, 65, 81, 0.95);
        }
        .product-list::-webkit-scrollbar {
            width: 5px;
        }
        .product-list::-webkit-scrollbar-track {
            background: #E5E7EB;
            border-radius: 8px;
        }
        .dark .product-list::-webkit-scrollbar-track {
            background: #374151;
        }
        .product-list::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 8px;
        }
        .dark .product-list::-webkit-scrollbar-thumb {
            background: #9CA3AF;
        }
        .alternate-row:nth-child(even) {
            background: rgba(0, 0, 0, 0.1);
        }
        .dark .alternate-row:nth-child(even) {
            background: rgba(0, 0, 0, 0.3);
        }
        .highlight-gold {
            color: #D4AF37;
            font-weight: 600;
            text-shadow: 0 0 4px rgba(212, 175, 55, 0.5);
        }
        .highlight-pos {
            color: #FFFFFF;
            font-weight: 500;
            text-shadow: 0 0 2px rgba(255, 255, 255, 0.5);
        }
        .transaction-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: rgba(249, 250, 251, 0.95);
            font-size: 0.875rem;
        }
        .dark .transaction-details-grid {
            background: rgba(55, 65, 81, 0.95);
        }
        .transaction-details-grid .label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #2D3748;
            text-transform: uppercase;
            line-height: 1.4;
        }
        .dark .transaction-details-grid .label {
            color: #CBD5E0;
        }
        .transaction-details-grid .value {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1A202C;
            line-height: 1.4;
            overflow-wrap: break-word;
        }
        .dark .transaction-details-grid .value {
            color: #E2E8F0;
        }
        .transaction-details-grid > div {
            padding: 0.5rem;
            border-bottom: 1px solid #E5E7EB;
        }
        .dark .transaction-details-grid > div {
            border-bottom: 1px solid #4B5563;
        }
        .transaction-details-grid .full-span {
            grid-column: 1 / -1;
            border-bottom: none;
        }
        @media print {
            body * { visibility: hidden; }
            body::before { display: none; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
        @media (max-width: 640px) {
            body {
                font-size: 14px;
                background-size: auto 100%;
                padding: 0.5rem;
            }
            .container {
                padding: 0.5rem;
            }
            .grid {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }
            .btn-primary {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            input, select, textarea {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            .card {
                padding: 0.75rem;
            }
            h1 {
                font-size: 1.75rem;
                margin-bottom: 0.25rem;
            }
            h2 {
                font-size: 1rem;
            }
            .product-list {
                max-height: 80px;
                padding: 0.5rem;
                max-width: 100%;
                font-size: 0.75rem;
            }
            .transaction-details-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
                padding: 0.5rem;
            }
            .transaction-details-grid .full-span {
                grid-column: 1 / -1;
            }
            .transaction-details-grid .label {
                font-size: 0.625rem;
                line-height: 1.3;
            }
            .transaction-details-grid .value {
                font-size: 0.75rem;
                line-height: 1.3;
            }
            .badge {
                font-size: 0.625rem;
                padding: 0.3rem 0.75rem;
            }
            .header-buttons {
                display: flex;
                flex-direction: row;
                gap: 0.75rem;
                justify-content: flex-end;
                width: 100%;
            }
        }
        @media (min-width: 641px) and (max-width: 1023px) {
            body {
                background-size: cover;
            }
            .container {
                padding: 1rem;
            }
            .grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .btn-primary {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }
            input, select, textarea {
                padding: 0.625rem;
                font-size: 0.875rem;
            }
            .card {
                padding: 1rem;
            }
            h1 {
                font-size: 2rem;
            }
            h2 {
                font-size: 1.25rem;
            }
            .product-list {
                max-height: 100px;
                padding: 0.5rem;
                max-width: 100%;
                font-size: 0.75rem;
            }
            .transaction-details-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
                padding: 0.75rem;
            }
            .transaction-details-grid .full-span {
                grid-column: 1 / -1;
            }
            .header-buttons {
                display: flex;
                flex-direction: row;
                gap: 1rem;
                justify-content: flex-end;
            }
        }
        @media (min-width: 1024px) {
            body {
                background-size: cover;
            }
            .desktop-mode .container {
                padding: 1.5rem;
            }
            .desktop-mode .grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.25rem;
            }
            .desktop-mode .btn-primary {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            .desktop-mode input,
            .desktop-mode select,
            .desktop-mode textarea {
                padding: 0.75rem;
                font-size: 1rem;
            }
            .desktop-mode .card {
                padding: 1.25rem;
            }
            .desktop-mode h1 {
                font-size: 2.5rem;
            }
            .desktop-mode h2 {
                font-size: 1.75rem;
            }
            .desktop-mode .product-list {
                max-height: 120px;
                padding: 0.5rem;
                max-width: 100%;
                font-size: 0.875rem;
            }
            .desktop-mode .transaction-details-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                padding: 0.75rem;
            }
            .desktop-mode .transaction-details-grid .full-span {
                grid-column: 1 / -1;
            }
            .desktop-mode .header-buttons {
                display: flex;
                flex-direction: row;
                gap: 1.25rem;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()" x-init="init()">
    <main class="container mx-auto px-4 sm:px-6 py-12 max-w-7xl">
        <!-- Dark Mode Toggle -->
        <div class="mb-4 flex justify-end">
            <button @click="toggleDarkMode" class="btn-primary px-3 py-1.5 text-sm flex items-center hover-scale">
                <svg x-show="!darkMode" class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="darkMode" class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
            </button>
        </div>

        <!-- Back to Dashboard -->
        <div class="mb-4 text-right">
            <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="btn-primary px-3 py-1.5 text-sm flex items-center hover-scale">
                <svg class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-gray-900 dark:text-gray-100 p-3 rounded-lg fade-in flex items-center justify-between" role="alert" x-ref="successAlert">
            <div class="flex items-center">
                <div class="bg-green-custom rounded-full p-1.5 mr-2">
                    <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" class="btn-primary px-3 py-1.5 text-xs flex items-center hover-scale">
                <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </a>
            @endif
            <button @click="dismissAlert" class="text-gray-medium dark:text-gray-400 hover:text-black-custom p-1 rounded-full hover-scale">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Error Alert -->
        <div x-show="errorMessage" class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-gray-900 dark:text-gray-100 p-3 rounded-lg fade-in flex items-center justify-between" role="alert" x-ref="errorAlert">
            <div class="flex items-center">
                <div class="bg-red-500 rounded-full p-1.5 mr-2">
                    <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="font-medium text-sm" x-text="errorMessage"></p>
            </div>
            <button @click="dismissAlert" class="text-gray-medium dark:text-gray-400 hover:text-black-custom p-1 rounded-full hover-scale">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Page Header -->
        <div class="card rounded-lg mb-8 p-4 fade-in" style="background-color: #2C3E50; border: none;">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2.5rem sm:text-3.5rem font-bold text-gray-100 flex items-center">
                        <svg class="h-8 w-8 mr-2 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="highlight-gold">Sepatu by Sovan</span>
                    </h1>
                    <p class="text-base text-gray-300 mt-4 highlight-pos">Point Of Sale</p>
                </div>
                <div class="header-buttons">
                    <a href="{{ route('transactions.create') }}" class="btn-primary px-4 py-2 text-sm flex items-center hover-scale">
                        <svg class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="{{ route('transactions.report') }}" class="btn-primary px-4 py-2 text-sm flex items-center hover-scale">
                        Laporan Penjualan
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card rounded-lg mb-8 fade-in" style="background-color: #2C3E50; border: none;">
            <div class="px-4 py-3 flex justify-between items-center border-b border-gray-600">
                <h2 class="text-base font-semibold text-gray-100 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-300 hover:text-white p-1.5 rounded-full hover-scale">
                    <svg x-show="showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal</label>
                        <input type="date" x-model="dateFilter" @change="fetchTransactions" class="w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="fetchTransactions" class="w-full text-sm">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">QRIS</option>
                            <option value="debit">Debit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="fetchTransactions" class="w-full text-sm">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="resetFilters" class="btn-primary px-4 py-2 text-sm flex items-center hover-scale">
                        <svg class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Notes Modal -->
        <div x-show="showNotesModal" class="modal-overlay" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-1" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-1" x-transition:leave-end="opacity-0">
            <div class="card p-4 max-w-sm">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Catatan Transaksi</h2>
                    <button @click="closeNotesModal" class="text-gray-medium dark:text-gray-400 hover:text-black-custom p-1.5 rounded-full hover-scale">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan (misal: salah metode pembayaran, mesin error)</label>
                    <textarea x-model="currentNote" rows="4" class="w-full text-sm" placeholder="Masukkan catatan untuk transaksi ini..."></textarea>
                </div>
                <div class="mt-3 flex justify-end gap-3">
                    <button @click="closeNotesModal" class="btn-primary px-4 py-2 text-sm flex items-center hover-scale">
                        Batal
                    </button>
                    <button @click="saveNote" class="btn-primary px-4 py-2 text-sm flex items-center hover-scale">
                        <svg class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Card Grid -->
        <div class="card rounded-lg p-4 fade-in">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-8">
                <div class="flex justify-center items-center">
                    <svg class="animate-spin h-6 w-6 text-black-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V1l4 4-4 4z"></path>
                    </svg>
                    <span class="ml-2 text-gray-medium dark:text-gray-400 text-sm">Memuat transaksi...</span>
                </div>
            </div>

            <!-- Transactions Grid -->
            <div x-show="!loading && filteredTransactions.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                    <div :class="{'bg-orange-custom/10 dark:bg-orange-custom/20': isNewTransaction(transaction.id), 'alternate-row': true}" class="card p-3 transition-all duration-200 hover-scale">
                        <div class="flex flex-col gap-3">
                            <!-- Transaction Header -->
                            <div class="flex justify-between items-start border-b border-gray-200 dark:border-gray-600 pb-2">
                                <div>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="transaction.invoice_number"></span>
                                    <div class="text-sm text-gray-medium dark:text-gray-400" x-text="formatTime(transaction.created_at)"></div>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="openNotesModal(transaction.id, transaction.note)" class="inline-flex items-center p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-full hover:bg-black-custom hover:text-white hover-scale" title="Tambah/Edit Catatan">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', transaction.id)" class="inline-flex items-center p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-full hover:bg-black-custom hover:text-white hover-scale" title="Cetak Struk">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <!-- Transaction Details -->
                            <div class="transaction-details-grid">
                                <div>
                                    <span class="label">Pelanggan</span>
                                    <div class="value" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-sm text-gray-medium dark:text-gray-400" x-text="transaction.customer_phone || '-'"></div>
                                </div>
                                <div>
                                    <span class="label">Produk</span>
                                    <div class="product-list" x-html="getProductNames(transaction.items)"></div>
                                </div>
                                <div>
                                    <span class="label">Metode</span>
                                    <div>
                                        <span class="badge" :class="{
                                            'badge-success': transaction.payment_method === 'cash',
                                            'badge-neutral': transaction.payment_method === 'credit_card',
                                            'badge-info': transaction.payment_method === 'transfer' || transaction.payment_method === 'debit'
                                        }" x-text="translatePaymentMethod(transaction.payment_method, transaction.card_type)"></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="label">Diskon</span>
                                    <div class="value text-gold-custom" x-text="formatRupiah(calculateDiscount(transaction))"></div>
                                </div>
                                <div>
                                    <span class="label">Total</span>
                                    <div class="value text-gold-custom" x-text="formatRupiah(transaction.final_amount)"></div>
                                </div>
                                <div>
                                    <span class="label">Status</span>
                                    <div>
                                        <span class="badge" :class="{
                                            'badge-success': transaction.payment_status === 'paid',
                                            'badge-warning': transaction.payment_status === 'pending',
                                            'badge-danger': transaction.payment_status === 'cancelled'
                                        }" x-text="translateStatus(transaction.payment_status)"></span>
                                    </div>
                                </div>
                                <div class="full-span">
                                    <span class="label">Catatan</span>
                                    <div class="value" x-text="transaction.note || '-'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="!loading && filteredTransactions.length === 0" class="text-center py-8">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg inline-block p-5">
                    <svg class="h-8 w-8 text-black-custom/50 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-3">Tidak Ada Transaksi</h3>
                <p class="text-sm text-gray-medium dark:text-gray-400 max-w-xs mx-auto mt-1">Belum ada transaksi untuk filter yang dipilih.</p>
                <div class="mt-4">
                    <a href="{{ route('transactions.create') }}" class="btn-primary px-4 py-2 text-sm flex items-center mx-auto hover-scale">
                        <svg class="h-4 w-4 mr-1 text-gold-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Transaksi Baru
                    </a>
                </div>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && filteredTransactions.length > 0" class="flex flex-col sm:flex-row items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-600 mt-6">
                <div class="text-sm text-gray-medium dark:text-gray-400 mb-2 sm:mb-0">
                    Menampilkan <span class="font-medium text-gray-900 dark:text-gray-100" x-text="paginationFrom()"></span> - <span x-text="paginationTo()"></span> dari <span class="font-medium text-gray-900 dark:text-gray-100" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-x-3 gap-y-2 w-full sm:w-auto">
                    <select x-model.number="perPage" @change="fetchTransactions" class="px-3 py-2 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 w-full sm:w-auto">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="flex space-x-2">
                        <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-black-custom hover:text-white" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <template x-for="page in displayedPages()" :key="page">
                            <button x-show="page !== '…'" @click="goToPage(page)" class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-black-custom hover:text-white" :class="{'bg-black-custom text-white': page === currentPage}">
                                <span x-text="page"></span>
                            </button>
                            <span x-show="page === '…'" class="px-3 py-2 text-gray-medium dark:text-gray-400">…</span>
                        </template>
                        <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-black-custom hover:text-white" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Print Button -->
        <div x-show="newTransactionId" class="fixed bottom-6 right-6" x-cloak>
            <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', newTransactionId)" class="flex items-center justify-center h-12 w-12 bg-gold-custom text-white rounded-full hover:bg-yellow-600 hover-scale">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    desktopMode: false,
                    showNotesModal: false,
                    currentNote: '',
                    currentTransactionId: null,
                    darkMode: false,

                    init() {
                        const savedDarkMode = localStorage.getItem('darkMode');
                        this.darkMode = savedDarkMode ? JSON.parse(savedDarkMode) : window.matchMedia('(prefers-color-scheme: dark)').matches;
                        
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }

                        this.$watch('darkMode', value => {
                            localStorage.setItem('darkMode', JSON.stringify(value));
                            document.documentElement.classList.toggle('dark', value);
                        });

                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                            if (!localStorage.getItem('darkMode')) {
                                this.darkMode = e.matches;
                            }
                        });

                        const today = new Date().toLocaleDateString('en-CA', { timeZone: 'Asia/Jakarta' });
                        this.dateFilter = today;
                        this.fetchTransactions();
                        if (this.newTransactionId) {
                            this.$nextTick(() => {
                                this.highlightNewTransaction();
                            });
                        }
                    },

                    toggleDarkMode() {
                        this.darkMode = !this.darkMode;
                    },

                    isMobile() {
                        return window.innerWidth <= 640;
                    },

                    toggleDesktopMode() {
                        this.desktopMode = !this.desktopMode;
                        const viewport = document.getElementById('viewport');
                        if (this.desktopMode) {
                            viewport.setAttribute('content', 'width=1024, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes');
                            document.body.classList.add('desktop-mode');
                        } else {
                            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                            document.body.classList.remove('desktop-mode');
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
                            const storedNotes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                            this.transactions = data.transactions.map(transaction => ({
                                ...transaction,
                                total_amount: parseFloat(transaction.total_amount) || 0,
                                final_amount: parseFloat(transaction.final_amount) || 0,
                                note: storedNotes[transaction.id] || ''
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
                                const newCard = document.querySelector(`div.bg-orange-custom\\/10`);
                                if (newCard) {
                                    newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

                    translatePaymentMethod(method, cardType) {
                        const methods = {
                            cash: 'Tunai',
                            credit_card: 'QRIS',
                            debit: cardType ? `Debit (${cardType})` : 'Debit',
                            transfer: 'Transfer Bank'
                        };
                        return methods[method] || method;
                    },

                    translateStatus(status) {
                        return {
                            paid: 'Lunas',
                            pending: 'Pending',
                            cancelled: 'Dibatalkan'
                        }[status] || status;
                    },

                    getProductNames(items) {
                        if (!items || !items.length) return '<span class="text-sm text-gray-medium dark:text-gray-400">-</span>';
                        return items.map(item => `
                            <div class="flex items-center space-x-1">
                                <span class="text-sm text-gray-900 dark:text-gray-100 whitespace-normal overflow-wrap-break-word">
                                    ${item.product && item.product.name ? item.product.name : '-'}
                                </span>
                                <span class="text-xs text-gray-medium dark:text-gray-400">
                                    (${item.product && item.product.size ? item.product.size : '-'}${item.product && item.product.color ? ', ' + item.product.color : ''})
                                </span>
                            </div>
                        `).join('');
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

                    openNotesModal(transactionId, note) {
                        this.currentTransactionId = transactionId;
                        this.currentNote = note || '';
                        this.showNotesModal = true;
                    },

                    closeNotesModal() {
                        this.showNotesModal = false;
                        this.currentTransactionId = null;
                        this.currentNote = '';
                    },

                    saveNote() {
                        if (this.currentTransactionId) {
                            const transaction = this.transactions.find(t => t.id === this.currentTransactionId);
                            if (transaction) {
                                transaction.note = this.currentNote.trim();
                                this.filteredTransactions = [...this.transactions];
                            }
                            const storedNotes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                            storedNotes[this.currentTransactionId] = this.currentNote.trim();
                            localStorage.setItem('transactionNotes', JSON.stringify(storedNotes));
                        }
                        this.closeNotesModal();
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
    </main>
</body>
</html>