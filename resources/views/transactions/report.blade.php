<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Flatpickr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Fallback Inline CSS -->
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4ff; }
        .container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        h1 { font-size: 1.875rem; font-weight: bold; color: #1a202c; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; }
        th { background: #4c51bf; color: white; }
        tr:nth-child(even) { background: #f7fafc; }
        a { color: #4c51bf; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .shadow-lg { box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); }
        .rounded-xl { border-radius: 0.75rem; }
        input[type="date"] { cursor: pointer; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 font-sans min-h-screen">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8 max-w-7xl">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">Laporan Transaksi</h1>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Transaksi
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('transactions.report') }}" id="filter-form" class="mb-8 bg-white p-6 rounded-xl shadow-lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                        <input type="date" name="date" id="date" value="{{ isset($date) ? $date : '' }}"
                               placeholder="Pilih tanggal"
                               class="pl-10 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Kasir</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                            <select name="user_id" id="user_id"
                                    class="pl-10 mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>

        <!-- Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <i class="fas fa-dollar-sign text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Total Penjualan</p>
                    <p class="text-2xl font-bold text-gray-800">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Jumlah Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $totalTransactions }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No. Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->customer_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada transaksi ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: "{{ isset($date) ? $date : '' }}",
            locale: {
                firstDayOfWeek: 1 // Mulai dari Senin
            },
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('filter-form').submit();
            }
        });
    </script>
</body>
</html>