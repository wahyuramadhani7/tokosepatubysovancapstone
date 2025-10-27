<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Laporan Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'black-custom': '#292929',
                        'orange-custom': '#FF4500',
                        'green-custom': '#4ADE80',
                        'gray-dark': '#374151',
                        'gray-medium': '#9CA3AF',
                        'blue-custom': '#2C3E50',
                        'gold-custom': '#E8C565'
                    },
                    fontFamily: {
                        'libre-baskerville': ['Libre Baskerville', 'serif']
                    }
                }
            },
            darkMode: 'class'
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Libre Baskerville', serif;
            background: url('images/bgapp.jpg') no-repeat center center fixed, #F3F4F6;
            background-size: cover;
            color: #1F2937;
            min-height: 100vh;
            padding: 0;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.75;
            position: relative;
            margin: 0;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(243, 244, 246, 0.9);
            z-index: -1;
        }
        .dark body::before {
            background: rgba(31, 41, 55, 0.9);
        }
        .dark body {
            color: #F3F4F6;
        }
        .custom-header {
            background-color: #292929;
            padding: 0.75rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
            margin: 0;
            left: 0;
            right: 0;
            min-height: 60px;
        }
        .custom-header .dashboard-button {
            background-color: #FF4500;
            color: #FFFFFF;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-right: 0.75rem;
        }
        .custom-header .dashboard-button:hover {
            background-color: #FF5722;
            transform: scale(1.05);
        }
        .dark-mode-toggle {
            background-color: #FF4500;
            color: #FFFFFF;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .dark-mode-toggle:hover {
            background-color: #FF5722;
            transform: scale(1.05);
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #D1D5DB;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .dark .card {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid #4B5563;
        }
        .header-section {
            background: #292929;
            padding: 1.75rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .header-section h1 {
            color: #E8C565;
            font-size: 1.75rem;
            font-weight: bold;
        }
        .header-section p {
            color: #FFFFFF;
            font-size: 1.125rem;
        }
        .header-buttons a, .header-buttons button {
            background: #FF4500;
            color: #FFFFFF;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(255, 69, 0, 0.2);
        }
        .header-buttons a:hover, .header-buttons button:hover {
            background: #FF5722;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 87, 34, 0.3);
        }
        .filter-section {
            background: #292929;
            padding: 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .filter-section label {
            color: #FFFFFF;
            font-size: 1rem;
            font-weight: 500;
        }
        .filter-section input, .filter-section select {
            background: #374151;
            border: 1px solid #6B7280;
            color: #F3F4F6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 2.75rem;
            width: 100%;
        }
        .filter-section input:focus, .filter-section select:focus {
            outline: 2px solid #FF4500;
            box-shadow: 0 4px 8px rgba(255, 69, 0, 0.2);
        }
        .filter-section button {
            background: #FF4500;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 2.75rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .filter-section button:hover {
            background: #FF5722;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 87, 34, 0.3);
        }
        .transaction-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #D1D5DB;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease-in;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .dark .transaction-card {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid #4B5563;
        }
        /* Updated transaction-details styles */
        .transaction-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            padding-top: 0.5rem;
        }
        @media (min-width: 640px) {
            .transaction-details {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                gap: 1.5rem;
                padding-bottom: 0.5rem;
                scrollbar-width: thin;
                scrollbar-color: #FF4500 #D1D5DB;
            }
            .transaction-details::-webkit-scrollbar {
                height: 6px;
            }
            .transaction-details::-webkit-scrollbar-track {
                background: #D1D5DB;
                border-radius: 4px;
            }
            .transaction-details::-webkit-scrollbar-thumb {
                background: #FF4500;
                border-radius: 4px;
            }
            .dark .transaction-details::-webkit-scrollbar-track {
                background: #4B5563;
            }
            .dark .transaction-details::-webkit-scrollbar-thumb {
                background: #FF5722;
            }
            .transaction-details > div {
                flex: 0 0 auto;
                min-width: 200px;
            }
            .transaction-details > div:not(:last-child) {
                border-top: none;
                padding-top: 0;
            }
        }
        @media (max-width: 639px) {
            .transaction-details > div:not(:last-child) {
                border-top: 1px solid #D1D5DB;
                padding-top: 0.5rem;
            }
            .dark .transaction-details > div:not(:last-child) {
                border-top: 1px solid #4B5563;
            }
        }
        .transaction-item {
            border-bottom: 1px solid #D1D5DB;
            padding-bottom: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .dark .transaction-item {
            border-bottom: 1px solid #4B5563;
        }
        .transaction-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .toggle-button {
            cursor: pointer;
            color: #FF4500;
        }
        .dark .toggle-button {
            color: #FF5722;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #D1D5DB;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FF4500;
            border-radius: 4px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #4B5563;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FF5722;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 640px) {
            body {
                font-size: 16px;
            }
            .header-section h1 {
                font-size: 1.5rem;
            }
            .header-section p {
                font-size: 1rem;
            }
            .header-buttons a, .header-buttons button {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }
            .filter-section {
                padding: 1.25rem;
            }
            .filter-section input, .filter-section select, .filter-section button {
                font-size: 0.875rem;
                padding: 0.625rem;
                height: 2.5rem;
            }
            .filter-section label {
                font-size: 0.875rem;
            }
            .transaction-card {
                padding: 1rem;
            }
            .custom-header {
                min-height: 50px;
                padding: 0.5rem 0;
            }
            .custom-header .dashboard-button {
                margin-right: 0.5rem;
            }
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionReportApp">
    <!-- Custom Header -->
    <header class="custom-header">
        <div class="logo" style="margin-left: 15px;">
            <img src="{{ asset('images/logo2.jpg') }}" alt="Sepatu by Sovan Logo" class="h-12 w-auto sm:h-12 md:h-14" loading="lazy">
        </div>
        <div class="flex items-center space-x-3">
            <button @click="toggleDarkMode" class="dark-mode-toggle" :title="darkMode ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'" aria-label="Ganti mode tema">
                <svg x-show="!darkMode" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="darkMode" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 01 8.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            <a href="{{ Auth::user()->role === 'owner' ? route('owner.dashboard') : route('employee.dashboard') }}" class="dashboard-button card-hover" title="Kembali ke Dashboard" aria-label="Kembali ke Dashboard">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 py-12 max-w-7xl">
        <!-- Page Header -->
        <div class="header-section">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="flex items-center">
                        <svg class="h-7 w-7 mr-2 text-orange-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Laporan Transaksi
                    </h1>
                    <p class="highlight-pos text-sm">
                        Ringkasan Penjualan 
                        @if ($reportType === 'monthly')
                            Bulanan
                        @elseif ($reportType === 'weekly')
                            Mingguan ({{ \Carbon\Carbon::parse($weekRange['start'])->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($weekRange['end'])->translatedFormat('d F Y') }})
                        @else
                            Harian
                        @endif
                    </p>
                </div>
                <div class="header-buttons flex gap-2">
                    <a href="{{ route('transactions.index') }}" class="flex items-center">
                        Kembali ke Transaksi
                    </a>
                    <button @click="exportToPDF()" class="flex items-center">
                        <svg class="h-5 w-5 mr-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('transactions.report') }}" id="filter-form" class="filter-section" x-data="{ showFilters: true, reportType: '{{ $reportType }}' }">
            <div class="flex justify-between items-center border-b border-gray-600 pb-3 mb-6">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="h-6 w-6 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Laporan
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-300 hover:text-white p-1.5 rounded-full" aria-label="Toggle filter">
                    <svg x-show="showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="grid grid-cols-1 sm:grid-cols-3 gap-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="flex flex-col space-y-2">
                    <label for="report_type" class="block text-base font-medium text-white">Tipe Laporan</label>
                    <select name="report_type" id="report_type" x-model="reportType" @change="toggleFilters(); document.getElementById('filter-form').submit()" class="w-full">
                        <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $reportType === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div x-show="reportType === 'daily' || reportType === 'weekly'" x-transition id="date-filter" class="flex flex-col space-y-2">
                    <label for="date" class="block text-base font-medium text-white">Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ $reportType === 'daily' || $reportType === 'weekly' ? $date : '' }}" class="w-full">
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="month-filter" class="flex flex-col space-y-2">
                    <label for="month" class="block text-base font-medium text-white">Bulan</label>
                    <select name="month" id="month" class="w-full">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="year-filter" class="flex flex-col space-y-2">
                    <label for="year" class="block text-base font-medium text-white">Tahun</label>
                    <select name="year" id="year" class="w-full">
                        @foreach (range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div class="flex flex-col space-y-2">
                        <label for="user_id" class="block text-base font-medium text-white">Kasir</label>
                        <select name="user_id" id="user_id" class="w-full">
                            <option value="">Semua Kasir</option>
                            @foreach (\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex flex-col space-y-2">
                    <label for="product_search" class="block text-base font-medium text-white">Cari Produk</label>
                    <input type="text" name="product_search" id="product_search" value="{{ $productSearch ?? '' }}" placeholder="Masukkan nama produk" class="w-full">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full sm:w-auto flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="card p-4 flex items-center fade-in">
                <div class="bg-orange-custom rounded-full p-3 mr-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Penjualan 
                        @if ($reportType === 'monthly')
                            Bulanan
                        @elseif ($reportType === 'weekly')
                            Mingguan
                        @else
                            Harian
                        @endif
                    </p>
                    <p class="text-lg font-bold text-orange-custom">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="card p-4 flex items-center fade-in">
                <div class="bg-orange-custom rounded-full p-3 mr-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 14" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Jumlah Transaksi 
                        @if ($reportType === 'monthly')
                            Bulanan
                        @elseif ($reportType === 'weekly')
                            Mingguan
                        @else
                            Harian
                        @endif
                    </p>
                    <p class="text-lg font-bold text-orange-custom">
                        {{ $totalTransactions }}
                    </p>
                </div>
            </div>
            <div class="card p-4 flex items-center fade-in">
                <div class="bg-orange-custom rounded-full p-3 mr-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0l-3-3m3 3l-3 3m-2 6H7m10 0l-3 3m3-3l-3-3" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Diskon 
                        @if ($reportType === 'monthly')
                            Bulanan
                        @elseif ($reportType === 'weekly')
                            Mingguan
                        @else
                            Harian
                        @endif
                    </p>
                    <p class="text-lg font-bold text-orange-custom">
                        Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="card p-4 flex items-center fade-in">
                <div class="bg-orange-custom rounded-full p-3 mr-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Produk Terjual 
                        @if ($reportType === 'monthly')
                            Bulanan
                        @elseif ($reportType === 'weekly')
                            Mingguan
                        @else
                            Harian
                        @endif
                    </p>
                    <p class="text-lg font-bold text-orange-custom">
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
            <div class="card mb-4 fade-in">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Metode Pembayaran: {{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}
                    </h2>
                </div>
                <div class="p-4">
                    @forelse ($methodTransactions as $index => $transaction)
                        <div class="transaction-card mb-3" x-data="{ showProducts: false }">
                            <div class="transaction-details">
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">No. Invoice</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->invoice_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Tanggal</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Kasir</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Pelanggan</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->customer_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Total</p>
                                    <p class="text-base font-semibold text-orange-custom">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Diskon</p>
                                    <p class="text-base font-semibold text-orange-custom">Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Catatan</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100" x-text="getTransactionNote({{ $transaction->id }}) || '-'"></p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">
                                    <span @click="showProducts = !showProducts" class="toggle-button">
                                        Produk
                                        <svg x-show="!showProducts" class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <svg x-show="showProducts" class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </span>
                                </p>
                                <div x-show="showProducts" x-transition>
                                    @php
                                        $filteredItems = $productSearch
                                            ? $transaction->items->filter(function ($item) use ($productSearch) {
                                                  return stripos($item->product->name ?? '', $productSearch) !== false;
                                              })
                                            : $transaction->items;
                                    @endphp
                                    @forelse ($filteredItems as $item)
                                        <div class="transaction-item">
                                            <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Nama:</span> {{ $item->product->name ?? '-' }}</p>
                                            <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Ukuran:</span> {{ $item->product->size ?? '-' }}</p>
                                            <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Warna:</span> {{ $item->product->color ?? '-' }}</p>
                                            <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Kode Unit:</span> {{ $item->productUnit->unit_code ?? '-' }}</p>
                                            <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Harga:</span> Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-medium dark:text-gray-400">Tidak ada produk yang cocok.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-base text-gray-medium dark:text-gray-400 text-center">Tidak ada transaksi ditemukan.</p>
                    @endforelse
                </div>
                <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Penjualan ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-base font-bold text-orange-custom">
                            Rp {{ number_format($methodTransactions->sum('final_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Diskon ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-base font-bold text-orange-custom">
                            Rp {{ number_format($methodTransactions->sum('discount_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Produk Terjual ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-base font-bold text-orange-custom">
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
                <div class="card mb-4 fade-in">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            Metode Pembayaran: Debit ({{ $cardType }})
                        </h2>
                    </div>
                    <div class="p-4">
                        @forelse ($paymentMethods[$method] as $index => $transaction)
                            <div class="transaction-card mb-3" x-data="{ showProducts: false }">
                                <div class="transaction-details">
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">No. Invoice</p>
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->invoice_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Tanggal</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Kasir</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Pelanggan</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->customer_name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Total</p>
                                        <p class="text-base font-semibold text-orange-custom">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Diskon</p>
                                        <p class="text-base font-semibold text-orange-custom">Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Catatan</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100" x-text="getTransactionNote({{ $transaction->id }}) || '-'"></p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">
                                        <span @click="showProducts = !showProducts" class="toggle-button">
                                            Produk
                                            <svg x-show="!showProducts" class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <svg x-show="showProducts" class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </span>
                                    </p>
                                    <div x-show="showProducts" x-transition>
                                        @php
                                            $filteredItems = $productSearch
                                                ? $transaction->items->filter(function ($item) use ($productSearch) {
                                                      return stripos($item->product->name ?? '', $productSearch) !== false;
                                                  })
                                                : $transaction->items;
                                        @endphp
                                        @forelse ($filteredItems as $item)
                                            <div class="transaction-item">
                                                <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Nama:</span> {{ $item->product->name ?? '-' }}</p>
                                                <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Ukuran:</span> {{ $item->product->size ?? '-' }}</p>
                                                <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Warna:</span> {{ $item->product->color ?? '-' }}</p>
                                                <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Kode Unit:</span> {{ $item->productUnit->unit_code ?? '-' }}</p>
                                                <p class="text-sm text-gray-900 dark:text-gray-100"><span class="font-medium">Harga:</span> Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-medium dark:text-gray-400">Tidak ada produk yang cocok.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-base text-gray-medium dark:text-gray-400 text-center">Tidak ada transaksi ditemukan.</p>
                        @endforelse
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                            Total Penjualan (Debit {{ $cardType }}): 
                            <span class="text-base font-bold text-orange-custom">
                                Rp {{ number_format($paymentMethods[$method]->sum('final_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                            Total Diskon (Debit {{ $cardType }}): 
                            <span class="text-base font-bold text-orange-custom">
                                Rp {{ number_format($paymentMethods[$method]->sum('discount_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                            Total Produk Terjual (Debit {{ $cardType }}): 
                            <span class="text-base font-bold text-orange-custom">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
            document.getElementById('month')?.addEventListener('change', () => filterForm.submit());
            document.getElementById('year')?.addEventListener('change', () => filterForm.submit());
            document.getElementById('user_id')?.addEventListener('change', () => filterForm.submit());
            document.getElementById('product_search')?.addEventListener('input', debounce(() => filterForm.submit(), 500));
            document.getElementById('date')?.addEventListener('change', () => filterForm.submit());
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionReportApp', () => ({
                darkMode: localStorage.getItem('darkMode') === 'true',
                showFilters: true,
                reportType: '{{ $reportType }}',
                init() {
                    this.$watch('darkMode', value => {
                        localStorage.setItem('darkMode', value);
                        document.documentElement.classList.toggle('dark', value);
                    });
                    document.documentElement.classList.toggle('dark', this.darkMode);
                    this.toggleFilters();
                },
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                },
                toggleFilters() {
                    const dateFilter = document.getElementById('date-filter');
                    const monthFilter = document.getElementById('month-filter');
                    const yearFilter = document.getElementById('year-filter');
                    if (this.reportType === 'monthly') {
                        dateFilter.style.display = 'none';
                        monthFilter.style.display = 'block';
                        yearFilter.style.display = 'block';
                        document.getElementById('date').value = '';
                    } else {
                        dateFilter.style.display = 'block';
                        monthFilter.style.display = 'none';
                        yearFilter.style.display = 'none';
                        document.getElementById('month').value = '{{ \Carbon\Carbon::now()->format('m') }}';
                        document.getElementById('year').value = '{{ \Carbon\Carbon::now()->year }}';
                    }
                },
                getTransactionNote(transactionId) {
                    const notes = JSON.parse(localStorage.getItem('transactionNotes') || '{}');
                    return notes[transactionId] || '';
                },
                exportToPDF() {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });

                    // Set font to match the webpage
                    doc.setFont('Libre Baskerville', 'normal');
                    doc.setFontSize(12);

                    // Header
                    doc.setFontSize(16);
                    doc.setFont('Libre Baskerville', 'bold');
                    doc.text('Laporan Transaksi - Sepatu by Sovan', 10, 10);
                    doc.setFontSize(10);
                    doc.setFont('Libre Baskerville', 'normal');
                    const reportPeriod = this.reportType === 'monthly' ? 'Bulanan' : 
                                       this.reportType === 'weekly' ? 'Mingguan' : 'Harian';
                    doc.text(`Ringkasan Penjualan ${reportPeriod}`, 10, 20);
                    doc.setLineWidth(0.5);
                    doc.line(10, 25, 200, 25);

                    // Summary Section
                    doc.setFontSize(12);
                    doc.setFont('Libre Baskerville', 'bold');
                    doc.text('Ringkasan', 10, 35);
                    doc.setFontSize(10);
                    doc.setFont('Libre Baskerville', 'normal');
                    doc.text(`Total Penjualan: Rp ${'{{ number_format($totalSales, 0, ",", ".") }}'}`, 10, 45);
                    doc.text(`Jumlah Transaksi: ${'{{ $totalTransactions }}'}`, 10, 55);
                    doc.text(`Total Diskon: Rp ${'{{ number_format($totalDiscount, 0, ",", ".") }}'}`, 10, 65);
                    doc.text(`Total Produk Terjual: ${'{{ $totalProductsSold }}'}`, 10, 75);
                    doc.line(10, 80, 200, 80);

                    // Transactions by Payment Method
                    let yPosition = 90;
                    const pageHeight = doc.internal.pageSize.height;
                    const marginBottom = 20;

                    // Helper function to check and add new page if needed
                    const checkPageBreak = (requiredHeight) => {
                        if (yPosition + requiredHeight > pageHeight - marginBottom) {
                            doc.addPage();
                            yPosition = 10;
                        }
                    };

                    // Non-Debit Payment Methods
                    @foreach ($nonDebitMethods as $method => $methodTransactions)
                        checkPageBreak(20);
                        doc.setFontSize(12);
                        doc.setFont('Libre Baskerville', 'bold');
                        doc.text(`Metode Pembayaran: {{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}`, 10, yPosition);
                        yPosition += 10;

                        @forelse ($methodTransactions as $transaction)
                            checkPageBreak(60);
                            doc.setFontSize(10);
                            doc.setFont('Libre Baskerville', 'normal');
                            doc.text(`No. Invoice: {{ $transaction->invoice_number }}`, 10, yPosition);
                            doc.text(`Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}`, 10, yPosition + 5);
                            doc.text(`Kasir: {{ $transaction->user->name }}`, 10, yPosition + 10);
                            doc.text(`Pelanggan: {{ $transaction->customer_name ?? '-' }}`, 10, yPosition + 15);
                            doc.text(`Total: Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}`, 10, yPosition + 20);
                            doc.text(`Diskon: Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}`, 10, yPosition + 25);
                            doc.text(`Catatan: ${this.getTransactionNote({{ $transaction->id }}) || '-'}`, 10, yPosition + 30);
                            yPosition += 35;

                            // Products
                            checkPageBreak(20);
                            doc.text('Produk:', 15, yPosition);
                            yPosition += 5;

                            @php
                                $filteredItems = $productSearch
                                    ? $transaction->items->filter(function ($item) use ($productSearch) {
                                          return stripos($item->product->name ?? '', $productSearch) !== false;
                                      })
                                    : $transaction->items;
                            @endphp
                            @forelse ($filteredItems as $item)
                                checkPageBreak(25);
                                doc.text(`Nama: {{ $item->product->name ?? '-' }}`, 20, yPosition);
                                doc.text(`Ukuran: {{ $item->product->size ?? '-' }}`, 20, yPosition + 5);
                                doc.text(`Warna: {{ $item->product->color ?? '-' }}`, 20, yPosition + 10);
                                doc.text(`Kode Unit: {{ $item->productUnit->unit_code ?? '-' }}`, 20, yPosition + 15);
                                doc.text(`Harga: Rp {{ number_format($item->subtotal, 0, ',', '.') }}`, 20, yPosition + 20);
                                yPosition += 25;
                            @empty
                                doc.text('Tidak ada produk yang cocok.', 20, yPosition);
                                yPosition += 10;
                            @endforelse
                            yPosition += 5;
                        @empty
                            checkPageBreak(10);
                            doc.text('Tidak ada transaksi ditemukan.', 10, yPosition);
                            yPosition += 10;
                        @endforelse

                        checkPageBreak(20);
                        doc.text(`Total Penjualan: Rp {{ number_format($methodTransactions->sum('final_amount'), 0, ',', '.') }}`, 10, yPosition);
                        doc.text(`Total Diskon: Rp {{ number_format($methodTransactions->sum('discount_amount'), 0, ',', '.') }}`, 10, yPosition + 5);
                        doc.text(`Total Produk Terjual: ${'{{ $methodTransactions->sum(function ($transaction) use ($productSearch) { return $productSearch ? $transaction->items->filter(function ($item) use ($productSearch) { return stripos($item->product->name ?? '', $productSearch) !== false; })->sum('quantity') : $transaction->items->sum('quantity'); }) }}'}`, 10, yPosition + 10);
                        yPosition += 20;
                        doc.line(10, yPosition, 200, yPosition);
                        yPosition += 5;
                    @endforeach

                    // Debit Payment Methods
                    @foreach ($debitMethods as $method => $cardType)
                        @if ($paymentMethods->has($method))
                            checkPageBreak(20);
                            doc.setFontSize(12);
                            doc.setFont('Libre Baskerville', 'bold');
                            doc.text(`Metode Pembayaran: Debit ({{ $cardType }})`, 10, yPosition);
                            yPosition += 10;

                            @forelse ($paymentMethods[$method] as $transaction)
                                checkPageBreak(60);
                                doc.setFontSize(10);
                                doc.setFont('Libre Baskerville', 'normal');
                                doc.text(`No. Invoice: {{ $transaction->invoice_number }}`, 10, yPosition);
                                doc.text(`Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}`, 10, yPosition + 5);
                                doc.text(`Kasir: {{ $transaction->user->name }}`, 10, yPosition + 10);
                                doc.text(`Pelanggan: {{ $transaction->customer_name ?? '-' }}`, 10, yPosition + 15);
                                doc.text(`Total: Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}`, 10, yPosition + 20);
                                doc.text(`Diskon: Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}`, 10, yPosition + 25);
                                doc.text(`Catatan: ${this.getTransactionNote({{ $transaction->id }}) || '-'}`, 10, yPosition + 30);
                                yPosition += 35;

                                // Products
                                checkPageBreak(20);
                                doc.text('Produk:', 15, yPosition);
                                yPosition += 5;

                                @php
                                    $filteredItems = $productSearch
                                        ? $transaction->items->filter(function ($item) use ($productSearch) {
                                              return stripos($item->product->name ?? '', $productSearch) !== false;
                                          })
                                        : $transaction->items;
                                @endphp
                                @forelse ($filteredItems as $item)
                                    checkPageBreak(25);
                                    doc.text(`Nama: {{ $item->product->name ?? '-' }}`, 20, yPosition);
                                    doc.text(`Ukuran: {{ $item->product->size ?? '-' }}`, 20, yPosition + 5);
                                    doc.text(`Warna: {{ $item->product->color ?? '-' }}`, 20, yPosition + 10);
                                    doc.text(`Kode Unit: {{ $item->productUnit->unit_code ?? '-' }}`, 20, yPosition + 15);
                                    doc.text(`Harga: Rp {{ number_format($item->subtotal, 0, ',', '.') }}`, 20, yPosition + 20);
                                    yPosition += 25;
                                @empty
                                    doc.text('Tidak ada produk yang cocok.', 20, yPosition);
                                    yPosition += 10;
                                @endforelse
                                yPosition += 5;
                            @empty
                                checkPageBreak(10);
                                doc.text('Tidak ada transaksi ditemukan.', 10, yPosition);
                                yPosition += 10;
                            @endforelse

                            checkPageBreak(20);
                            doc.text(`Total Penjualan: Rp {{ number_format($paymentMethods[$method]->sum('final_amount'), 0, ',', '.') }}`, 10, yPosition);
                            doc.text(`Total Diskon: Rp {{ number_format($paymentMethods[$method]->sum('discount_amount'), 0, ',', '.') }}`, 10, yPosition + 5);
                            doc.text(`Total Produk Terjual: ${'{{ $paymentMethods[$method]->sum(function ($transaction) use ($productSearch) { return $productSearch ? $transaction->items->filter(function ($item) use ($productSearch) { return stripos($item->product->name ?? '', $productSearch) !== false; })->sum('quantity') : $transaction->items->sum('quantity'); }) }}'}`, 10, yPosition + 10);
                            yPosition += 20;
                            doc.line(10, yPosition, 200, yPosition);
                            yPosition += 5;
                        @endif
                    @endforeach

                    // Save PDF
                    doc.save(`Laporan_Transaksi_${this.reportType}_${new Date().toISOString().slice(0, 10)}.pdf`);
                }
            }));
        });
    </script>
</body>
</html>