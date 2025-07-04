<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Lora', serif;
            background: linear-gradient(135deg, #FAFAFA 0%, #F3F4F6 100%);
            color: #1F2937;
            min-height: 100vh;
            padding-top: 5rem;
            position: relative;
            overflow-x: hidden;
        }
        .dark body {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            color: #F3F4F6;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150"%3E%3Cg fill="%23D4AF37" fill-opacity="0.05"%3E%3Ccircle cx="75" cy="75" r="75"/%3E%3C/g%3E%3C/svg%3E');
            z-index: -1;
            animation: particleMove 30s linear infinite;
        }
        .dark body::before {
            background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150"%3E%3Cg fill="%23D4AF37" fill-opacity="0.02"%3E%3Ccircle cx="75" cy="75" r="75"/%3E%3C/g%3E%3C/svg%3E');
        }
        @keyframes particleMove {
            0% { background-position: 0 0; }
            100% { background-position: 150px 150px; }
        }
        .hover-scale:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(90deg, #065F46, #10b981);
            color: #FAFAFA;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            border: 1px solid #D4AF37;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
        .dark .btn-primary {
            background: linear-gradient(90deg, #047857, #34d399);
            border: 1px solid #FBBF24;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #064E3B, #059669);
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }
        .dark .btn-primary:hover {
            background: linear-gradient(90deg, #065F46, #10b981);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.5);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
            transition: 0.4s;
        }
        .dark .btn-primary::after {
            background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.3), transparent);
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .header {
            background: rgba(255, 255, 255, 0.98);
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .dark .header {
            background: rgba(31, 41, 55, 0.98);
            border-bottom: 1px solid rgba(251, 191, 36, 0.2);
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #D4AF37;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .dark .card {
            background: #1F2937;
            border: 1px solid #FBBF24;
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.15);
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.25);
        }
        .dark .card:hover {
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.25);
        }
        .badge {
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .badge-success {
            background: rgba(6, 95, 70, 0.15);
            color: #10b981;
            border: 1px solid rgba(6, 95, 70, 0.3);
        }
        .dark .badge-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-warning {
            background: rgba(234, 179, 8, 0.15);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }
        .dark .badge-warning {
            background: rgba(251, 191, 36, 0.15);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }
        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .dark .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-neutral {
            background: rgba(107, 114, 128, 0.15);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }
        .dark .badge-neutral {
            background: rgba(209, 213, 219, 0.15);
            color: #d1d5db;
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .badge-info {
            background: rgba(6, 95, 70, 0.15);
            color: #10b981;
            border: 1px solid rgba(6, 95, 70, 0.3);
        }
        .dark .badge-info {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 12px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 12px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FBBF24;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #B8972F;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }
        input, select {
            background: #F9FAFB;
            border: 1px solid #D4AF37;
            color: #1F2937;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        .dark input, .dark select {
            background: #374151;
            border: 1px solid #FBBF24;
            color: #F3F4F6;
        }
        input:focus, select:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
            outline: none;
        }
        .dark input:focus, .dark select:focus {
            border-color: #FBBF24;
            box-shadow: 0 0 8px rgba(251, 191, 36, 0.5);
        }
        tbody tr:hover {
            background: rgba(212, 175, 55, 0.1) !important;
        }
        .dark tbody tr:hover {
            background: rgba(251, 191, 36, 0.1) !important;
        }
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
        .glow:hover {
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.5);
        }
        .dark .glow:hover {
            box-shadow: 0 0 12px rgba(251, 191, 36, 0.5);
        }
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            max-height: 120px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #D4AF37 #F3F4F6;
        }
        .dark .product-list {
            scrollbar-color: #FBBF24 #374151;
        }
        .product-list::-webkit-scrollbar {
            width: 4px;
        }
        .product-list::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 8px;
        }
        .dark .product-list::-webkit-scrollbar-track {
            background: #374151;
        }
        .product-list::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 8px;
        }
        .dark .product-list::-webkit-scrollbar-thumb {
            background: #FBBF24;
        }
        .product-list::-webkit-scrollbar-thumb:hover {
            background: #B8972F;
        }
        .dark .product-list::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .grid {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
            .btn-primary {
                padding: 0.65rem 1.25rem;
                font-size: 0.95rem;
            }
            input, select {
                padding: 0.65rem;
                font-size: 0.95rem;
            }
            .card {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.75rem;
            }
            h2 {
                font-size: 1.25rem;
            }
            th, td {
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            .product-list {
                max-height: 100px;
            }
        }
        @media (min-width: 641px) {
            .desktop-mode .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .desktop-mode .grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
            .desktop-mode .btn-primary {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            .desktop-mode input,
            .desktop-mode select {
                padding: 0.75rem;
                font-size: 1rem;
            }
            .desktop-mode .card {
                padding: 2rem;
            }
            .desktop-mode h1 {
                font-size: 2.25rem;
            }
            .desktop-mode h2 {
                font-size: 1.5rem;
            }
            .desktop-mode th,
            .desktop-mode td {
                font-size: 0.875rem;
                padding: 1rem;
            }
            .desktop-mode .product-list {
                max-height: 120px;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#065F46',
                            secondary: '#10b981',
                            gold: '#D4AF37',
                            dark: {
                                50: '#F9FAFB',
                                100: '#F3F4F6',
                                200: '#E5E7EB',
                                300: '#D1D5DB',
                                400: '#9CA3AF',
                                500: '#6B7280',
                                600: '#4B5563',
                                700: '#374151',
                                800: '#1F2937',
                                900: '#111827',
                            },
                        }
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionListApp()" x-init="init()">
    <!-- Header -->
    <header class="fixed top-0 w-full header text-brand-dark-800 dark:text-brand-dark-50 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-brand-primary rounded-lg p-2 glow hover-scale">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-['Cinzel'] font-bold text-2xl sm:text-3xl text-brand-dark-800 dark:text-brand-dark-50">Sepatu by Sovan</h1>
                    <p class="text-sm text-brand-dark-600 dark:text-brand-dark-400">Luxury Footwear Collection</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="darkMode = !darkMode" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 dark:text-brand-dark-50 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" :title="darkMode ? 'Mode Terang' : 'Mode Gelap'">
                    <svg x-show="!darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <button x-show="isMobile()" @click="toggleDesktopMode" class="sm:hidden p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 dark:text-brand-dark-50 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" :title="desktopMode ? 'Kembali ke Mode Mobile' : 'Mode Desktop'">
                    <svg x-show="!desktopMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <svg x-show="desktopMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </button>
                <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 dark:text-brand-dark-50 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" title="Dashboard">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-7-7v14" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-10 max-w-7xl">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-brand-dark-800 dark:text-brand-dark-100 p-5 rounded-xl fade-in flex items-center justify-between" role="alert" x-ref="successAlert">
            <div class="flex items-center">
                <div class="bg-brand-primary rounded-full p-2 mr-4">
                    <svg class="h-6 w-6 text-brand-gold" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="font-semibold text-base font-['Cinzel']">{{ session('success') }}</p>
            </div>
            @if(session('transaction_id'))
            <a href="{{ route('transactions.print', session('transaction_id')) }}" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center hover-scale ripple">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
            </a>
            @endif
            <button @click="dismissAlert" class="text-brand-dark-600 dark:text-brand-dark-300 hover:text-brand-gold p-2 rounded-full hover:bg-brand-dark-100 dark:hover:bg-brand-dark-700 hover-scale glow">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        <!-- Error Alert -->
        <div x-show="errorMessage" class="mb-8 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-brand-dark-800 dark:text-brand-dark-100 p-5 rounded-xl fade-in flex items-center justify-between" role="alert" x-ref="errorAlert">
            <div class="flex items-center">
                <div class="bg-red-500 rounded-full p-2 mr-4">
                    <svg class="h-6 w-6 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="font-semibold text-base font-['Cinzel']" x-text="errorMessage"></p>
            </div>
            <button @click="dismissAlert" class="text-brand-dark-600 dark:text-brand-dark-300 hover:text-brand-gold p-2 rounded-full hover:bg-brand-dark-100 dark:hover:bg-brand-dark-700 hover-scale glow">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Page Header -->
        <div class="card rounded-xl mb-8 p-6 sm:p-8 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="font-['Cinzel'] text-2xl sm:text-3xl font-bold text-brand-dark-800 dark:text-brand-dark-50 flex items-center">
                        <svg class="h-8 w-8 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Sistem Kasir
                    </h1>
                    <p class="text-base text-brand-dark-600 dark:text-brand-dark-400 mt-2">Daftar Transaksi</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('transactions.create') }}" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center hover-scale ripple">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="{{ route('transactions.report') }}" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center hover-scale ripple">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m6 2v-6m6 6v-8m-6 8H9m12 0H3" />
                        </svg>
                        Laporan Penjualan
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div x-data="{ showFilters: true }" class="card rounded-xl mb-8 fade-in">
            <div class="px-6 py-4 flex justify-between items-center border-b border-brand-gold/20 dark:border-brand-gold/40">
                <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 dark:text-brand-dark-50 flex items-center font-['Cinzel']">
                    <svg class="h-6 w-6 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Transaksi
                </h2>
                <button @click="showFilters = !showFilters" class="text-brand-dark-600 dark:text-brand-dark-300 hover:text-brand-gold p-2 rounded-full hover:bg-brand-dark-100 dark:hover:bg-brand-dark-700 hover-scale glow">
                    <svg x-show="showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="p-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Tanggal</label>
                        <input type="date" x-model="dateFilter" @change="fetchTransactions" class="w-full text-base sm:text-lg">
                    </div>
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Metode Pembayaran</label>
                        <select x-model="paymentMethodFilter" @change="fetchTransactions" class="w-full text-base sm:text-lg">
                            <option value="">Semua Metode</option>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">QRIS</option>
                            <option value="debit">Debit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Status Pembayaran</label>
                        <select x-model="statusFilter" @change="fetchTransactions" class="w-full text-base sm:text-lg">
                            <option value="">Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="resetFilters" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center hover-scale ripple">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card rounded-xl overflow-hidden fade-in">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-brand-gold/20 dark:divide-brand-gold/40">
                    <thead>
                        <tr class="bg-brand-dark-50 dark:bg-brand-dark-800">
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider cursor-pointer font-['Cinzel']" @click="sortBy('id')">
                                <div class="flex items-center">
                                    ID
                                    <span x-show="sortColumn === 'id'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase cursor-pointer font-['Cinzel']" @click="sortBy('date')">
                                <div class="flex items-center">
                                    Jam
                                    <span x-show="sortColumn === 'date'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Produk</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Metode</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Diskon</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase cursor-pointer font-['Cinzel']" @click="sortBy('total')">
                                <div class="flex items-center">
                                    Total
                                    <span x-show="sortColumn === 'total'" x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Status</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase font-['Cinzel']">Aksi</th>
                        </tr>
                    </thead>
                    <tbody x-show="loading" class="text-center py-12">
                        <tr>
                            <td colspan="9">
                                <div class="flex justify-center items-center">
                                    <svg class="animate-spin h-8 w-8 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V1l4 4-4 4z"></path>
                                    </svg>
                                    <span class="ml-3 text-brand-dark-600 dark:text-brand-dark-300 text-base">Memuat transaksi...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody x-show="!loading && filteredTransactions.length > 0">
                        <template x-for="transaction in paginatedTransactions" :key="transaction.id">
                            <tr :class="{'bg-brand-gold/10 dark:bg-brand-gold/20': isNewTransaction(transaction.id)}" class="transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-base font-medium text-brand-dark-800 dark:text-brand-dark-100" x-text="transaction.invoice_number"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-brand-dark-600 dark:text-brand-dark-400" x-text="formatTime(transaction.created_at)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-base font-medium text-brand-dark-800 dark:text-brand-dark-100" x-text="transaction.customer_name || 'Tanpa Nama'"></div>
                                    <div class="text-sm text-brand-dark-600 dark:text-brand-dark-400" x-text="transaction.customer_phone || '-'"></div>
                                </td>
                                <td class="px-6 py-4" aria-label="Daftar Produk">
                                    <div class="product-list" x-html="getProductNames(transaction.items)"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge" :class="{
                                        'badge-success': transaction.payment_method === 'cash',
                                        'badge-neutral': transaction.payment_method === 'credit_card',
                                        'badge-info': transaction.payment_method === 'transfer' || transaction.payment_method === 'debit'
                                    }" x-text="translatePaymentMethod(transaction.payment_method, transaction.card_type)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-base font-medium text-brand-gold" x-text="formatRupiah(calculateDiscount(transaction))"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-base font-medium text-brand-gold" x-text="formatRupiah(transaction.final_amount)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge" :class="{
                                        'badge-success': transaction.payment_status === 'paid',
                                        'badge-warning': transaction.payment_status === 'pending',
                                        'badge-danger': transaction.payment_status === 'cancelled'
                                    }" x-text="translateStatus(transaction.payment_status)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-3">
                                    <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', transaction.id)" class="inline-flex items-center p-2 bg-brand-dark-100 dark:bg-brand-dark-700 text-brand-dark-800 dark:text-brand-dark-100 rounded-lg hover:bg-brand-primary hover:text-brand-gold hover-scale glow" title="Cetak Struk">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <!-- Empty State -->
                    <tbody x-show="!loading && filteredTransactions.length === 0" class="text-center py-12">
                        <tr>
                            <td colspan="9">
                                <div class="bg-brand-dark-100 dark:bg-brand-dark-700 rounded-xl inline-block p-6">
                                    <svg class="h-12 w-12 text-brand-gold/50 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-semibold text-brand-dark-800 dark:text-brand-dark-100 font-['Cinzel'] mt-4">Tidak Ada Transaksi</h3>
                                <p class="text-base text-brand-dark-600 dark:text-brand-dark-400 max-w-sm mx-auto">Belum ada transaksi untuk filter yang dipilih.</p>
                                <div class="mt-6">
                                    <a href="{{ route('transactions.create') }}" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center mx-auto hover-scale ripple">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <div x-show="!loading && filteredTransactions.length > 0" class="flex items-center justify-between p-6 bg-brand-dark-50 dark:bg-brand-dark-800 border-t border-brand-gold/20 dark:border-brand-gold/40">
                <div class="text-base text-brand-dark-600 dark:text-brand-dark-300">
                    Menampilkan <span class="font-medium text-brand-dark-800 dark:text-brand-dark-100" x-text="paginationFrom()"></span> - <span x-text="paginationTo()"></span> dari <span class="font-medium text-brand-dark-800 dark:text-brand-dark-100" x-text="filteredTransactions.length"></span> transaksi
                </div>
                <div class="flex items-center gap-x-3">
                    <select x-model.number="perPage" @change="fetchTransactions" class="px-3 py-2 text-base rounded-lg bg-brand-dark-100 dark:bg-brand-dark-700 border border-brand-gold/20 dark:border-brand-gold/40 text-brand-dark-600 dark:text-brand-dark-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="flex space-x-2">
                        <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-2 rounded-lg bg-brand-dark-100 dark:bg-brand-dark-700 hover:bg-brand-primary hover:text-brand-gold" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <template x-for="page in displayedPages()" :key="page">
                            <button x-show="page !== '…'" @click="goToPage(page)" class="px-4 py-2 rounded-lg bg-brand-dark-100 dark:bg-brand-dark-700 hover:bg-brand-primary hover:text-brand-gold" :class="{'bg-brand-primary text-brand-gold': page === currentPage}">
                                <span x-text="page"></span>
                            </button>
                            <span x-show="page === '…'" class="px-4 py-2 text-brand-dark-600 dark:text-brand-dark-300">…</span>
                        </template>
                        <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-3 py-2 rounded-lg bg-brand-dark-100 dark:bg-brand-dark-700 hover:bg-brand-primary hover:text-brand-gold" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Print Button -->
        <div x-show="newTransactionId" class="fixed bottom-6 right-6" x-cloak>
            <a :href="`{{ route('transactions.print', ':id') }}`.replace(':id', newTransactionId)" class="flex items-center justify-center h-12 w-12 bg-brand-primary text-brand-gold rounded-full hover:bg-brand-secondary hover-scale glow">
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

                    init() {
                        // Load dark mode preference from localStorage
                        this.darkMode = localStorage.getItem('darkMode') === 'true';
                        this.$watch('darkMode', value => {
                            localStorage.setItem('darkMode', value);
                        });

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
                                const newRow = document.querySelector(`tr.bg-brand-gold/10`);
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
                        if (!items || !items.length) return '<span class="text-base text-brand-dark-600 dark:text-brand-dark-400">-</span>';
                        return items.map(item => `
                            <div class="flex items-center space-x-2">
                                <span class="text-base text-brand-dark-800 dark:text-brand-dark-100 whitespace-normal overflow-wrap-break-word">
                                    ${item.product && item.product.name ? item.product.name : '-'}
                                </span>
                                <span class="text-sm text-brand-dark-600 dark:text-brand-dark-400">
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