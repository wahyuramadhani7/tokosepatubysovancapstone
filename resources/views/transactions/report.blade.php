<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Flatpickr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            transition: transform 0.3s ease;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2rem;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        th {
            background: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        tr:hover {
            background: #e5e7eb;
            transition: background 0.2s ease;
        }
        a {
            color: #4f46e5;
            font-weight: 500;
        }
        a:hover {
            color: #7c3aed;
            text-decoration: none;
        }
        .shadow-custom {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .rounded-custom {
            border-radius: 1rem;
        }
        input[type="date"], select {
            transition: all 0.3s ease;
        }
        input[type="date"]:focus, select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .nested-table {
            margin-left: 1.5rem;
            width: calc(100% - 1.5rem);
            background: #f1f5f9;
            border-radius: 0.5rem;
        }
        .nested-table th, .nested-table td {
            padding: 0.75rem;
        }
        .nested-table th {
            background: #e2e8f0;
            color: #1e293b;
        }
        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #7c3aed);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #3b82f6, #6d28d9);
            transform: translateY(-1px);
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
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
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Desktop Mode Toggle Button -->
        <div class="desktop-mode mb-4">
            <button id="toggleDesktopMode"
                    class="inline-flex items-center px-4 py-2 btn-primary text-white rounded-lg shadow-md">
                <i class="fas fa-desktop mr-2"></i> Mode Desktop
            </button>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-10 bg-white rounded-custom shadow-custom p-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 sm:mb-0">Laporan Transaksi</h1>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center px-6 py-3 btn-primary text-white rounded-lg shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Transaksi
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('transactions.report') }}" id="filter-form" class="mb-10 bg-white p-8 rounded-custom shadow-custom">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="report_type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Laporan</label>
                    <div class="relative">
                        <i class="fas fa-chart-bar absolute left-4 top-3.5 text-gray-400"></i>
                        <select name="report_type" id="report_type" onchange="toggleFilters(this)"
                                class="pl-12 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>
                </div>
                <div id="daily-filter" class="{{ $reportType === 'monthly' ? 'hidden' : '' }}">
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="date" name="date" id="date" value="{{ $reportType === 'daily' ? $date : '' }}"
                               placeholder="Pilih tanggal"
                               class="pl-12 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div id="monthly-filter" class="{{ $reportType === 'daily' ? 'hidden' : '' }}">
                    <label for="month" class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-4 top-3.5 text-gray-400"></i>
                        <select name="month" id="month"
                                class="pl-12 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="year-filter" class="{{ $reportType === 'daily' ? 'hidden' : '' }}">
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-4 top-3.5 text-gray-400"></i>
                        <select name="year" id="year"
                                class="pl-12 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach (range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Kasir</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-3.5 text-gray-400"></i>
                            <select name="user_id" id="user_id"
                                    class="pl-12 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
            </div>
            <div class="mt-6">
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 btn-primary text-white rounded-lg shadow-md">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-custom shadow-custom card flex items-center">
                <div class="bg-indigo-100 p-4 rounded-full mr-4">
                    <i class="fas fa-dollar-sign text-indigo-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Total Penjualan {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-3xl font-bold text-gray-800">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-custom shadow-custom card flex items-center">
                <div class="bg-green-100 p-4 rounded-full mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Jumlah Transaksi {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $totalTransactions }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-custom shadow-custom card flex items-center">
                <div class="bg-yellow-100 p-4 rounded-full mr-4">
                    <i class="fas fa-tags text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Total Diskon {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-3xl font-bold text-gray-800">
                        Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-custom shadow-custom card flex items-center">
                <div class="bg-blue-100 p-4 rounded-full mr-4">
                    <i class="fas fa-box-open text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Total Produk Terjual {{ $reportType === 'monthly' ? 'Bulanan' : 'Harian' }}</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $totalProductsSold }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Transactions by Payment Method -->
        @php
            // Group transactions by payment_method
            $paymentMethods = $transactions->groupBy('payment_method');
            $debitMethods = [
                'debit_mandiri' => 'Mandiri',
                'debit_bri' => 'BRI',
                'debit_bca' => 'BCA'
            ];
            // Filter non-debit methods (cash, qris, transfer, etc.)
            $nonDebitMethods = $paymentMethods->filter(function ($value, $key) use ($debitMethods) {
                return !array_key_exists($key, $debitMethods);
            });
        @endphp

        <!-- Non-Debit Payment Methods -->
        @foreach ($nonDebitMethods as $method => $methodTransactions)
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    Metode Pembayaran: {{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}
                </h2>
                <div class="bg-white rounded-custom shadow-custom overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">No. Invoice</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Kasir</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Diskon</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($methodTransactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $transaction->customer_name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <table class="nested-table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-xs font-semibold text-gray-700">Nama Produk</th>
                                                        <th class="text-xs font-semibold text-gray-700">Ukuran</th>
                                                        <th class="text-xs font-semibold text-gray-700">Warna</th>
                                                        <th class="text-xs font-semibold text-gray-700">Kode Unit</th>
                                                        <th class="text-xs font-semibold text-gray-700">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transaction->items as $item)
                                                        <tr>
                                                            <td class="text-sm text-gray-600">{{ $item->product->name ?? '-' }}</td>
                                                            <td class="text-sm text-gray-600">{{ $item->product->size ?? '-' }}</td>
                                                            <td class="text-sm text-gray-600">{{ $item->product->color ?? '-' }}</td>
                                                            <td class="text-sm text-gray-600">{{ $item->productUnit->unit_code ?? '-' }}</td>
                                                            <td class="text-sm text-gray-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada transaksi ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 bg-white p-6 rounded-custom shadow-custom">
                    <p class="text-sm font-semibold text-gray-600">Total Penjualan ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-gray-800">
                            Rp {{ number_format($methodTransactions->sum('final_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-semibold text-gray-600">Total Diskon ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-gray-800">
                            Rp {{ number_format($methodTransactions->sum('discount_amount'), 0, ',', '.') }}
                        </span>
                    </p>
                    <p class="text-sm font-semibold text-gray-600">Total Produk Terjual ({{ ucfirst($method === 'qris' ? 'QRIS' : $method) }}): 
                        <span class="text-lg font-bold text-gray-800">
                            {{ $methodTransactions->sum(function ($transaction) { return $transaction->items->sum('quantity'); }) }}
                        </span>
                    </p>
                </div>
            </div>
        @endforeach

        <!-- Debit Payment Methods -->
        @foreach ($debitMethods as $method => $cardType)
            @if ($paymentMethods->has($method))
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        Metode Pembayaran: Debit ({{ $cardType }})
                    </h2>
                    <div class="bg-white rounded-custom shadow-custom overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">No. Invoice</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Kasir</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Pelanggan</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Diskon</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($paymentMethods[$method] as $transaction)
                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $transaction->customer_name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <table class="nested-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-xs font-semibold text-gray-700">Nama Produk</th>
                                                            <th class="text-xs font-semibold text-gray-700">Ukuran</th>
                                                            <th class="text-xs font-semibold text-gray-700">Warna</th>
                                                            <th class="text-xs font-semibold text-gray-700">Kode Unit</th>
                                                            <th class="text-xs font-semibold text-gray-700">Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transaction->items as $item)
                                                            <tr>
                                                                <td class="text-sm text-gray-600">{{ $item->product->name ?? '-' }}</td>
                                                                <td class="text-sm text-gray-600">{{ $item->product->size ?? '-' }}</td>
                                                                <td class="text-sm text-gray-600">{{ $item->product->color ?? '-' }}</td>
                                                                <td class="text-sm text-gray-600">{{ $item->productUnit->unit_code ?? '-' }}</td>
                                                                <td class="text-sm text-gray-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Tidak ada transaksi ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4 bg-white p-6 rounded-custom shadow-custom">
                        <p class="text-sm font-semibold text-gray-600">Total Penjualan (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-gray-800">
                                Rp {{ number_format($paymentMethods[$method]->sum('final_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-semibold text-gray-600">Total Diskon (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-gray-800">
                                Rp {{ number_format($paymentMethods[$method]->sum('discount_amount'), 0, ',', '.') }}
                            </span>
                        </p>
                        <p class="text-sm font-semibold text-gray-600">Total Produk Terjual (Debit {{ $cardType }}): 
                            <span class="text-lg font-bold text-gray-800">
                                {{ $paymentMethods[$method]->sum(function ($transaction) { return $transaction->items->sum('quantity'); }) }}
                            </span>
                        </p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function toggleFilters(select) {
            const dailyFilter = document.getElementById('daily-filter');
            const monthlyFilter = document.getElementById('monthly-filter');
            const yearFilter = document.getElementById('year-filter');

            if (select.value === 'monthly') {
                dailyFilter.classList.add('hidden');
                monthlyFilter.classList.remove('hidden');
                yearFilter.classList.remove('hidden');
                document.getElementById('date').value = ''; // Reset date input
            } else {
                dailyFilter.classList.remove('hidden');
                monthlyFilter.classList.add('hidden');
                yearFilter.classList.add('hidden');
                document.getElementById('month').value = '{{ \Carbon\Carbon::now()->format('m') }}'; // Reset to current month
                document.getElementById('year').value = '{{ \Carbon\Carbon::now()->year }}'; // Reset to current year
            }
        }

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

        // Desktop Mode Toggle
        document.getElementById('toggleDesktopMode').addEventListener('click', function() {
            const body = document.body;
            const viewport = document.getElementById('viewport');
            if (body.classList.contains('desktop-view')) {
                body.classList.remove('desktop-view');
                viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                this.innerHTML = '<i class="fas fa-desktop mr-2"></i> Mode Desktop';
            } else {
                body.classList.add('desktop-view');
                viewport.setAttribute('content', 'width=1280');
                this.innerHTML = '<i class="fas fa-mobile-alt mr-2"></i> Mode Mobile';
            }
        });

        // Submit form when month or year changes
        document.getElementById('month').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
        document.getElementById('year').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
        document.getElementById('user_id').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    </script>
</body>
</html>