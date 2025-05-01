@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap');
</style>
<div class="w-full">
    <!-- Hero Section with Background -->
    <div class="relative w-full h-48 md:h-64 lg:h-96 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/bgdashboard.png');">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
        <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-80 px-4 sm:px-6 md:px-8 py-3 md:py-4 rounded-lg z-10 w-4/5 sm:w-auto text-center">
            <h2 class="text-orange-500 font-bold text-xl sm:text-2xl" style="font-family: 'Libre Baskerville', serif;">@SEPATUBYSOVAN</h2>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="bg-cover bg-center" style="background-image: url('/images/bgapp.jpg');">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 md:py-6 bg-white bg-opacity-95">
            <!-- Daily Report Section -->
            <div class="mb-6 md:mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-center mb-4 md:mb-6">LAPORAN HARIAN</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <!-- Total Products -->
                    <div class="border border-blue-300 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-3 py-2">
                            <h3 class="text-gray-800 text-sm md:text-base font-medium">Total Produk</h3>
                        </div>
                        <div class="flex">
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6">
                                    <path d="M4 11c0 0 0.5-8 9-8s8.5 10 8.5 10l1.5 1v4c0 1.5-1 2-2 2h-17c-1 0-2-0.5-2-2v-4l2-3z"/>
                                    <rect x="2" y="14" width="20" height="2" fill="#FF4500"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-2 md:p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-lg md:text-xl font-bold">{{ $totalProducts }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Total Transactions -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-3 py-2">
                            <h3 class="text-gray-800 text-sm md:text-base font-medium">Total Transaksi</h3>
                        </div>
                        <div class="flex">
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6">
                                    <path d="M7 2v11h3v9l7-12h-4l4-8z"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-2 md:p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-lg md:text-xl font-bold">{{ $totalTransactions }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Total Sales -->
                    <div class="border border-blue-300 rounded-lg overflow-hidden sm:col-span-2 lg:col-span-1">
                        <div class="bg-blue-50 px-3 py-2">
                            <h3 class="text-gray-800 text-sm md:text-base font-medium">Total Penjualan</h3>
                        </div>
                        <div class="flex">
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6">
                                    <path d="M4 11c0 0 0.5-8 9-8s8.5 10 8.5 10l1.5 1v4c0 1.5-1 2-2 2h-17c-1 0-2-0.5-2-2v-4l2-3z"/>
                                    <rect x="2" y="14" width="20" height="2" fill="#FF4500"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-2 md:p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-lg md:text-xl font-bold truncate">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Pie Chart (Produk Terlaris) -->
                <div class="bg-gray-800 rounded-lg p-3 md:p-5 text-white">
                    <h3 class="text-base md:text-lg font-semibold mb-2 md:mb-3">Produk Terlaris</h3>
                    <div class="w-full h-48 md:h-56 lg:h-64">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <ul class="space-y-1">
                            @forelse ($topProducts as $index => $product)
                                <li class="flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ ['#FF4500', '#FFA500', '#FFD700', '#00CED1', '#4682B4'][$index] }}"></span>
                                    <span class="text-xs md:text-sm">{{ $product['name'] }} ({{ $product['quantity'] }})</span>
                                </li>
                            @empty
                                <li class="text-xs md:text-sm text-gray-400">Tidak ada data produk terlaris.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Line Chart (Grafik Transaksi Harian) -->
                <div class="bg-gray-800 rounded-lg p-3 md:p-5 text-white">
                    <h3 class="text-base md:text-lg font-semibold mb-0.5 md:mb-1">Grafik Transaksi Harian</h3>
                    <p class="text-2xs md:text-xs text-gray-400 mb-2 md:mb-4">Laporan Transaksi Hari Ini ({{ now()->format('d M Y') }})</p>
                    <div class="w-full h-48 md:h-56 lg:h-64">
                        <canvas id="hourlyTransactionsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Transactions Detail Section -->
            <div class="rounded-lg overflow-hidden border border-blue-100 mb-6 md:mb-8">
                <h3 class="text-lg md:text-xl font-semibold p-3 md:p-4 bg-white">Detail Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">ID</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Tanggal</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Customer</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Produk</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Harga</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Status</th>
                                <th class="px-2 sm:px-4 md:px-6 py-2 md:py-3 bg-gray-800 text-left text-2xs sm:text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        {{ $transaction->id }}
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        {{ $transaction->customer_name ?: 'Walk-in Customer' }}
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        @foreach ($transaction->items as $item)
                                            <div class="mb-1 last:mb-0">{{ $item->product->name }} ({{ $item->quantity }})</div>
                                        @endforeach
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        <span class="px-2 py-1 inline-flex text-2xs sm:text-xs leading-4 font-semibold rounded-full 
                                            {{ $transaction->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($transaction->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-400 hover:text-indigo-300">
                                            <span class="hidden sm:inline">Lihat</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:hidden inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-2 sm:px-4 md:px-6 py-2 md:py-4 whitespace-nowrap text-2xs sm:text-xs md:text-sm text-gray-300 bg-gray-700 border border-gray-600 text-center">
                                        Tidak ada transaksi hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart untuk Produk Terlaris
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'pie',
            data: {
                labels: [@foreach ($topProducts as $product) '{{ $product['name'] }}', @endforeach],
                datasets: [{
                    data: [@foreach ($topProducts as $product) {{ $product['quantity'] }}, @endforeach],
                    backgroundColor: ['#FF4500', '#FFA500', '#FFD700', '#00CED1', '#4682B4'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw + ' unit';
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Line Chart untuk Transaksi Harian
        const hourlyTransactionsCtx = document.getElementById('hourlyTransactionsChart').getContext('2d');
        new Chart(hourlyTransactionsCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: @json($hourlyData),
                    fill: true,
                    backgroundColor: 'rgba(192, 86, 33, 0.8)',
                    borderColor: '#C05621',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#9CA3AF'
                        },
                        grid: {
                            color: '#4B5563'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9CA3AF',
                            maxTicksLimit: 12, // Batasi jumlah label agar tidak terlalu padat
                            callback: function(value, index, values) {
                                // Tampilkan hanya beberapa label untuk kejelasan
                                return index % 2 === 0 ? this.getLabelForValue(value) : '';
                            }
                        },
                        grid: {
                            color: '#4B5563'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' transaksi';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection