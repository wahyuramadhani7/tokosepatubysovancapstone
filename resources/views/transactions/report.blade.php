<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Laporan Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        .glow:hover {
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.5);
        }
        .dark .glow:hover {
            box-shadow: 0 0 12px rgba(251, 191, 36, 0.5);
        }
        .nested-table {
            margin-left: 1.5rem;
            width: calc(100% - 1.5rem);
            background: #F9FAFB;
            border-radius: 0.5rem;
            border: 1px solid #D4AF37;
        }
        .dark .nested-table {
            background: #374151;
            border: 1px solid #FBBF24;
        }
        .nested-table th {
            background: #E5E7EB;
            color: #1F2937;
            font-family: 'Cinzel', serif;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .dark .nested-table th {
            background: #4B5563;
            color: #F3F4F6;
        }
        .nested-table td {
            padding: 0.75rem;
            font-size: 0.85rem;
        }
        .desktop-mode {
            display: none;
        }
        @media (max-width: 640px) {
            .desktop-mode {
                display: block;
            }
            .desktop-view {
                transform: scale(0.5);
                transform-origin: top left;
                width: 200%;
                overflow-x: auto;
            }
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
<body class="min-h-screen custom-scrollbar" x-data="transactionReportApp">
    <!-- Desktop Mode Toggle Button -->
    <div class="desktop-mode mb-4 px-4 sm:px-6">
        <button @click="toggleDesktopMode()" class="btn-primary px-4 py-2 text-base sm:text-lg flex items-center hover-scale" :title="desktopMode ? 'Mode Mobile' : 'Mode Desktop'">
            <svg x-show="!desktopMode" class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <svg x-show="desktopMode" class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span x-text="desktopMode ? 'Mode Mobile' : 'Mode Desktop'"></span>
        </button>
    </div>

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
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 dark:text-brand-dark-50 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" :title="darkMode ? 'Mode Terang' : 'Mode Gelap'">
                    <svg x-show="!darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <a href="{{ route('transactions.index') }}" class="p-2 bg-brand-dark-100 rounded-lg text-brand-dark-800 dark:text-brand-dark-50 hover:bg-brand-primary hover:text-brand-gold hover-scale glow" title="Kembali ke Transaksi">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-10 max-w-7xl" :class="{ 'desktop-view': desktopMode }">
        <!-- Page Header -->
        <div class="card rounded-xl mb-8 p-6 sm:p-8 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="font-['Cinzel'] text-2xl sm:text-3xl font-bold text-brand-dark-800 dark:text-brand-dark-50 flex items-center">
                        <svg class="h-8 w-8 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m6 2v-6m6 6v-8m-6 8H9m12 0H3" />
                        </svg>
                        Laporan Transaksi
                    </h1>
                    <p class="text-base text-brand-dark-600 dark:text-brand-dark-400 mt-2">Ringkasan Penjualan {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('transactions.report') }}" id="filter-form" class="card rounded-xl mb-8 p-6 sm:p-8 fade-in" x-data="{ showFilters: true, reportType: '{{ $reportType }}' }">
            <div class="flex justify-between items-center border-b border-brand-gold/20 dark:border-brand-gold/40 pb-4 mb-4">
                <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 dark:text-brand-dark-50 flex items-center font-['Cinzel']">
                    <svg class="h-6 w-6 mr-3 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Laporan
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
            <div x-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div>
                    <label for="report_type" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Tipe Laporan</label>
                    <div class="relative">
                        <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m6 2v-6m6 6v-8m-6 8H9m12 0H3" />
                        </svg>
                        <select name="report_type" id="report_type" x-model="reportType" @change="toggleFilters(); document.getElementById('filter-form').submit()" class="pl-10 w-full text-base sm:text-lg">
                            <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>
                </div>
                <div x-show="reportType === 'daily'" x-transition id="daily-filter">
                    <label for="date" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Tanggal</label>
                    <div class="relative">
                        <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <input type="date" name="date" id="date" value="{{ $reportType === 'daily' ? $date : '' }}" class="pl-10 w-full text-base sm:text-lg">
                    </div>
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="monthly-filter">
                    <label for="month" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Bulan</label>
                    <div class="relative">
                        <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <select name="month" id="month" class="pl-10 w-full text-base sm:text-lg">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="year-filter">
                    <label for="year" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Tahun</label>
                    <div class="relative">
                        <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <select name="year" id="year" class="pl-10 w-full text-base sm:text-lg">
                            @foreach (range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div>
                        <label for="user_id" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Kasir</label>
                        <div class="relative">
                            <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m-6 4H4m6 0v-4m0 11v-3" />
                            </svg>
                            <select name="user_id" id="user_id" class="pl-10 w-full text-base sm:text-lg">
                                <option value="">Semua Kasir</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div>
                    <label for="product_search" class="block text-sm sm:text-base font-medium text-brand-dark-600 dark:text-brand-dark-300 mb-2">Cari Produk</label>
                    <div class="relative">
                        <svg class="h-5 w-5 text-brand-gold absolute left-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1016.65 16.65z" />
                        </svg>
                        <input type="text" name="product_search" id="product_search" value="{{ $productSearch ?? '' }}" placeholder="Masukkan nama produk" class="pl-10 w-full text-base sm:text-lg">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-primary px-5 py-2 text-base sm:text-lg flex items-center hover-scale">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 flex items-center fade-in">
                <div class="bg-brand-primary rounded-full p-4 mr-4">
                    <svg class="h-8 w-8 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">Total Penjualan {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-2xl font-bold text-brand-dark-800 dark:text-brand-dark-50">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="card p-6 flex items-center fade-in">
                <div class="bg-brand-primary rounded-full p-4 mr-4">
                    <svg class="h-8 w-8 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">Jumlah Transaksi {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-2xl font-bold text-brand-dark-800 dark:text-brand-dark-50">
                        {{ $totalTransactions }}
                    </p>
                </div>
            </div>
            <div class="card p-6 flex items-center fade-in">
                <div class="bg-brand-primary rounded-full p-4 mr-4">
                    <svg class="h-8 w-8 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0l-3-3m3 3l-3 3m-2 6H7m10 0l-3 3m3-3l-3-3" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">Total Diskon {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-2xl font-bold text-brand-dark-800 dark:text-brand-dark-50">
                        Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="card p-6 flex items-center fade-in">
                <div class="bg-brand-primary rounded-full p-4 mr-4">
                    <svg class="h-8 w-8 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">Total Produk Terjual {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-2xl font-bold text-brand-dark-800 dark:text-brand-dark-50">
                        {{ $totalProductsSold }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Transactions by Payment Method -->
        @php
            $paymentMethods = $transactions->groupBy('payment_method');
            $debitMethods = [
                'debit_mandiri' => 'Mandiri',
                'debit_bri' => 'BRI',
                'debit_bca' => 'BCA'
            ];
            $nonDebitMethods = $paymentMethods->filter(function ($value, $key) use ($debitMethods) {
                return !array_key_exists($key, $debitMethods);
            });
        @endphp

        <!-- Non-Debit Payment Methods -->
        @foreach ($nonDebitMethods as $method => $methodTransactions)
            <div class="card rounded-xl mb-8 fade-in">
                <div class="px-6 py-4 border-b border-brand-gold/20 dark:border-brand-gold/40">
                    <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 dark:text-brand-dark-50 font-['Cinzel']">
                        Metode Pembayaran: {{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}
                    </h2>
                </div>
                <div class="overflow-x-auto custom-scrollbar p-6">
                    <table class="min-w-full divide-y divide-brand-gold/20 dark:divide-brand-gold/40">
                        <thead>
                            <tr class="bg-brand-dark-50 dark:bg-brand-dark-800">
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">No. Invoice</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Kasir</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Pelanggan</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Produk</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Diskon</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-gold/20 dark:divide-brand-gold/40">
                            @forelse ($methodTransactions as $transaction)
                                <tr class="transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-dark-800 dark:text-brand-dark-100">{{ $transaction->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-dark-600 dark:text-brand-dark-400">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-800 dark:text-brand-dark-100">{{ $transaction->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-800 dark:text-brand-dark-100">
                                        {{ $transaction->customer_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-brand-dark-600 dark:text-brand-dark-400">
                                        <table class="nested-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Nama Produk</th>
                                                    <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Ukuran</th>
                                                    <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Warna</th>
                                                    <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Kode Unit</th>
                                                    <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $filteredItems = $productSearch
                                                        ? $transaction->items->filter(function ($item) use ($productSearch) {
                                                              return stripos($item->product->name ?? '', $productSearch) !== false;
                                                          })
                                                        : $transaction->items;
                                                @endphp
                                                @forelse ($filteredItems as $item)
                                                    <tr>
                                                        <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->name ?? '-' }}</td>
                                                        <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->size ?? '-' }}</td>
                                                        <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->color ?? '-' }}</td>
                                                        <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->productUnit->unit_code ?? '-' }}</td>
                                                        <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-sm text-brand-dark-600 dark:text-brand-dark-400 text-center">Tidak ada produk yang cocok.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-gold">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-gold">Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-600 dark:text-brand-dark-400" x-text="getTransactionNote({{ $transaction->id }}) || '-'"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-base text-brand-dark-600 dark:text-brand-dark-400">
                                        Tidak ada transaksi ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-brand-gold/20 dark:border-brand-gold/40">
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                        Total Penjualan ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                            Rp {{ number_format($methodTransactions->sum('final_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                        Total Diskon ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                            Rp {{ number_format($methodTransactions->sum('discount_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                        Total Produk Terjual ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                            {{ $methodTransactions->sum(function ($transaction) use ($productSearch) {
                                return $productSearch
                                    ? $transaction->items->filter(function ($item) use ($productSearch) {
                                          return stripos($item->product->name ?? '', $productSearch) !== false;
                                      })->sum('quantity')
                                    : $transaction->items->sum('quantity');
                            }) }}
                        </span>
                    </p>
                </div>
            </div>
        @endforeach

        <!-- Debit Payment Methods -->
        @foreach ($debitMethods as $method => $cardType)
            @if ($paymentMethods->has($method))
                <div class="card rounded-xl mb-8 fade-in">
                    <div class="px-6 py-4 border-b border-brand-gold/20 dark:border-brand-gold/40">
                        <h2 class="text-xl sm:text-2xl font-semibold text-brand-dark-800 dark:text-brand-dark-50 font-['Cinzel']">
                            Metode Pembayaran: Debit ({{ $cardType }})
                        </h2>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar p-6">
                        <table class="min-w-full divide-y divide-brand-gold/20 dark:divide-brand-gold/40">
                            <thead>
                                <tr class="bg-brand-dark-50 dark:bg-brand-dark-800">
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">No. Invoice</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Kasir</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Produk</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Total</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Diskon</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-brand-dark-600 dark:text-brand-dark-300 uppercase tracking-wider font-['Cinzel']">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-gold/20 dark:divide-brand-gold/40">
                                @forelse ($paymentMethods[$method] as $transaction)
                                    <tr class="transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-dark-800 dark:text-brand-dark-100">{{ $transaction->invoice_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-dark-600 dark:text-brand-dark-400">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-800 dark:text-brand-dark-100">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-800 dark:text-brand-dark-100">
                                            {{ $transaction->customer_name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-brand-dark-600 dark:text-brand-dark-400">
                                            <table class="nested-table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Nama Produk</th>
                                                        <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Ukuran</th>
                                                        <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Warna</th>
                                                        <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Kode Unit</th>
                                                        <th class="text-xs font-semibold text-gray-700 dark:text-gray-200">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $filteredItems = $productSearch
                                                            ? $transaction->items->filter(function ($item) use ($productSearch) {
                                                                  return stripos($item->product->name ?? '', $productSearch) !== false;
                                                              })
                                                            : $transaction->items;
                                                    @endphp
                                                    @forelse ($filteredItems as $item)
                                                        <tr>
                                                            <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->name ?? '-' }}</td>
                                                            <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->size ?? '-' }}</td>
                                                            <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->product->color ?? '-' }}</td>
                                                            <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">{{ $item->productUnit->unit_code ?? '-' }}</td>
                                                            <td class="text-sm text-brand-dark-600 dark:text-brand-dark-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-sm text-brand-dark-600 dark:text-brand-dark-400 text-center">Tidak ada produk yang cocok.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-gold">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-brand-gold">Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-brand-dark-600 dark:text-brand-dark-400" x-text="getTransactionNote({{ $transaction->id }}) || '-'"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-base text-brand-dark-600 dark:text-brand-dark-400">
                                            Tidak ada transaksi ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-brand-gold/20 dark:border-brand-gold/40">
                        <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                            Total Penjualan (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                                Rp {{ number_format($paymentMethods[$method]->sum('final_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                            Total Diskon (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                                Rp {{ number_format($paymentMethods[$method]->sum('discount_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-brand-dark-600 dark:text-brand-dark-400">
                            Total Produk Terjual (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-brand-dark-800 dark:text-brand-dark-50">
                                {{ $paymentMethods[$method]->sum(function ($transaction) use ($productSearch) {
                                    return $productSearch
                                        ? $transaction->items->filter(function ($item) use ($productSearch) {
                                              return stripos($item->product->name ?? '', $productSearch) !== false;
                                          })->sum('quantity')
                                        : $transaction->items->sum('quantity');
                                }) }}
                            </span>
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    </main>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Flatpickr
            flatpickr("#date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                defaultDate: "{{ $reportType === 'daily' ? $date : '' }}",
                locale: {
                    firstDayOfWeek: 1 // Mulai dari Senin
                },
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById('filter-form').submit();
                }
            });

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Form submission on change
            const filterForm = document.getElementById('filter-form');
            document.getElementById('month').addEventListener('change', () => filterForm.submit());
            document.getElementById('year').addEventListener('change', () => filterForm.submit());
            document.getElementById('user_id')?.addEventListener('change', () => filterForm.submit());
            document.getElementById('product_search').addEventListener('input', debounce(() => filterForm.submit(), 500));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionReportApp', () => ({
                darkMode: localStorage.getItem('darkMode') === 'true',
                desktopMode: false,
                showFilters: true,
                reportType: '{{ $reportType }}',
                init() {
                    this.$watch('darkMode', value => {
                        localStorage.setItem('darkMode', value);
                        document.documentElement.classList.toggle('dark', value);
                    });
                    // Ensure initial dark mode state is applied
                    document.documentElement.classList.toggle('dark', this.darkMode);
                },
                toggleDesktopMode() {
                    this.desktopMode = !this.desktopMode;
                    const viewport = document.getElementById('viewport');
                    if (this.desktopMode) {
                        viewport.setAttribute('content', 'width=1280, initial-scale=1.0');
                        document.body.classList.add('desktop-view');
                    } else {
                        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                        document.body.classList.remove('desktop-view');
                    }
                },
                toggleFilters() {
                    const dailyFilter = document.getElementById('daily-filter');
                    const monthlyFilter = document.getElementById('monthly-filter');
                    const yearFilter = document.getElementById('year-filter');
                    if (this.reportType === 'monthly') {
                        dailyFilter.style.display = 'none';
                        monthlyFilter.style.display = 'block';
                        yearFilter.style.display = 'block';
                        document.getElementById('date').value = '';
                    } else {
                        dailyFilter.style.display = 'block';
                        monthlyFilter.style.display = 'none';
                        yearFilter.style.display = 'none';
                        document.getElementById('month').value = '{{ \Carbon\Carbon::now()->format('m') }}';
                        document.getElementById('year').value = '{{ \Carbon\Carbon::now()->year }}';
                    }
                },
                getTransactionNote(transactionId) {
                    const notes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                    return notes[transactionId] || '';
                }
            }));
        });

        // Debug CDN loading
        window.addEventListener('load', () => {
            if (!window.Alpine) console.error('Alpine.js failed to load. Check CDN or network.');
            if (!window.flatpickr) console.error('Flatpickr failed to load. Check CDN or network.');
        });
    </script>
</body>
</html>