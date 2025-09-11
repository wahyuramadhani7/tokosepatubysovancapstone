<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Sepatu by Sovan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange-custom': '#FF6B35',
                        'green-custom': '#4ADE80',
                        'gray-dark': '#374151',
                        'gray-medium': '#6B7280'
                    },
                    fontFamily: {
                        'libre-baskerville': ['Libre Baskerville', 'serif']
                    },
                    borderRadius: {
                        'xl': '1rem',
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
            background: #F3F4F6;
            color: #1F2937;
            min-height: 100vh;
            padding: 0.5rem;
        }
        .dark body {
            background: #1F2937;
            color: #F3F4F6;
        }
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        .btn-primary {
            background: #FF6B35;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .dark .btn-primary {
            background: #F97316;
        }
        .btn-primary:hover {
            background: #EA580C;
            transform: translateY(-2px);
        }
        .dark .btn-primary:hover {
            background: #FF6B35;
        }
        .btn-back {
            background: #FF6B35;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .dark .btn-back {
            background: #F97316;
        }
        .btn-back:hover {
            background: #EA580C;
            transform: translateY(-2px);
        }
        .dark .btn-back:hover {
            background: #FF6B35;
        }
        .btn-export {
            background: #4ADE80;
            color: #FFFFFF;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-export:hover {
            background: #22C55E;
            transform: translateY(-2px);
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #D1D5DB;
            border-radius: 1rem;
            transition: transform 0.2s ease;
        }
        .dark .card {
            background: #1F2937;
            border: 1px solid #4B5563;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .report-card {
            background: #292929;
            border: 1px solid #3D3D3D;
            border-radius: 1rem;
        }
        .dark .report-card {
            background: #292929;
            border: 1px solid #3D3D3D;
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
        input, select {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            color: #1F2937;
            border-radius: 0.5rem;
            padding: 0.5rem;
            transition: all 0.2s ease;
            font-family: 'Libre Baskerville', serif;
        }
        .dark input, .dark select {
            background: #374151;
            border: 1px solid #6B7280;
            color: #F3F4F6;
        }
        input:focus, select:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 6px rgba(255, 107, 53, 0.4);
            outline: none;
        }
        .dark input:focus, .dark select:focus {
            border-color: #F97316;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .transaction-card {
            background: #F9FAFB;
            border: 1px solid #D1D5DB;
            border-radius: 0.75rem;
            padding: 0.75rem;
        }
        .dark .transaction-card {
            background: #374151;
            border: 1px solid #6B7280;
        }
        .transaction-details > div:not(:last-child) {
            border-top: 1px solid #D1D5DB;
            padding-top: 0.75rem;
        }
        .dark .transaction-details > div:not(:last-child) {
            border-top: 1px solid #6B7280;
        }
        .transaction-item {
            border-bottom: 1px solid #D1D5DB;
            padding-bottom: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .dark .transaction-item {
            border-bottom: 1px solid #6B7280;
        }
        .transaction-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .toggle-button {
            cursor: pointer;
            color: #FF6B35;
        }
        .dark .toggle-button {
            color: #F97316;
        }
        @media (max-width: 640px) {
            .container {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
            .grid {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .btn-primary, .btn-back, .btn-export {
                padding: 0.4rem 0.8rem;
                font-size: 0.875rem;
            }
            input, select {
                padding: 0.4rem;
                font-size: 0.875rem;
            }
            .card {
                padding: 0.5rem;
                border-radius: 0.75rem;
            }
            .report-card {
                border-radius: 0.75rem;
            }
            h1 {
                font-size: 1.25rem;
            }
            h2 {
                font-size: 1rem;
            }
            .transaction-card {
                padding: 0.5rem;
                border-radius: 0.5rem;
            }
            .transaction-details {
                grid-template-columns: 1fr;
            }
            .transaction-details p {
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
            }
            .transaction-item p {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="min-h-screen custom-scrollbar" x-data="transactionReportApp">
    <!-- Main Content -->
    <main class="container mx-auto px-2 py-4 max-w-7xl">
        <!-- Page Header -->
        <div class="report-card mb-4 p-4 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-3 md:mb-0">
                    <h1 class="text-xl font-bold text-gray-100 dark:text-gray-200 flex items-center">
                        Laporan Transaksi
                    </h1>
                    <p class="text-gray-300 dark:text-gray-400 mt-1 text-base">
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
                <div class="flex gap-2">
                    <a href="{{ route('transactions.index') }}" class="btn-back flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Transaksi
                    </a>
                    <button @click="exportToPDF()" class="btn-export flex items-center">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('transactions.report') }}" id="filter-form" class="report-card mb-4 p-4 fade-in" x-data="{ showFilters: true, reportType: '{{ $reportType }}' }">
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-600 pb-3 mb-3">
                <h2 class="text-base font-semibold text-gray-100 dark:text-gray-200 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-custom" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Laporan
                </h2>
                <button @click="showFilters = !showFilters" class="text-gray-300 dark:text-gray-400 hover:text-orange-custom p-1 rounded-full hover-scale">
                    <svg x-show="showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg x-show="!showFilters" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Tipe Laporan</label>
                    <select name="report_type" id="report_type" x-model="reportType" @change="toggleFilters(); document.getElementById('filter-form').submit()" class="w-full">
                        <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $reportType === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div x-show="reportType === 'daily' || reportType === 'weekly'" x-transition id="date-filter">
                    <label for="date" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ $reportType === 'daily' || $reportType === 'weekly' ? $date : '' }}" class="w-full">
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="month-filter">
                    <label for="month" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Bulan</label>
                    <select name="month" id="month" class="w-full">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div x-show="reportType === 'monthly'" x-transition id="year-filter">
                    <label for="year" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Tahun</label>
                    <select name="year" id="year" class="w-full">
                        @foreach (range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Kasir</label>
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
                <div>
                    <label for="product_search" class="block text-sm font-medium text-gray-100 dark:text-gray-200 mb-1">Cari Produk</label>
                    <input type="text" name="product_search" id="product_search" value="{{ $productSearch ?? '' }}" placeholder="Masukkan nama produk" class="w-full">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn-primary flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
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
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
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
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
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
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
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
                            <div class="grid grid-cols-2 gap-2 transaction-details pt-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">No. Invoice</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->invoice_number }}</p>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mt-1 mb-1">Tanggal</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Kasir</p>
                                    <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</p>
                                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mt-1 mb-1">Pelanggan</p>
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
                                <div class="col-span-2">
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
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($methodTransactions->sum('final_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Diskon ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($methodTransactions->sum('discount_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                        Total Produk Terjual ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">
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
                                <div class="grid grid-cols-2 gap-2 transaction-details pt-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">No. Invoice</p>
                                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->invoice_number }}</p>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mt-1 mb-1">Tanggal</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mb-1">Kasir</p>
                                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</p>
                                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400 mt-1 mb-1">Pelanggan</p>
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
                                    <div class="col-span-2">
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
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($paymentMethods[$method]->sum('final_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                            Total Diskon (Debit {{ $cardType }}): 
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($paymentMethods[$method]->sum('discount_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-medium text-gray-medium dark:text-gray-400">
                            Total Produk Terjual (Debit {{ $cardType }}): 
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">
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