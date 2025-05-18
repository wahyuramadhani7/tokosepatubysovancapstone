@extends('layouts.app')

@section('content')
<!-- Add Libre Baskerville font from Google Fonts -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap');
</style>
<div class="w-full">
    <!-- Hero Section with Background - Increased height to h-96 -->
    <div class="relative w-full h-96 overflow-hidden">
        <!-- Background image -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/bgdashboard.png');">
            <!-- Overlay to ensure text is readable -->
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
        
        <!-- Brand Tag - Centered and font changed to Libre Baskerville -->
        <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-80 px-8 py-4 rounded-lg z-10">
            <h2 class="text-orange-500 font-bold text-2xl" style="font-family: 'Libre Baskerville', serif;">@SEPATUBYSOVAN</h2>
        </div>
    </div>

    <!-- Main Dashboard Content with bgapp.jpg background -->
    <div class="bg-cover bg-center" style="background-image: url('/images/bgapp.jpg');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-white bg-opacity-95">
            <!-- Daily Report Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-center mb-6">LAPORAN HARIAN</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Product Card -->
                    <div class="border border-blue-300 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-4 py-2">
                            <h3 class="text-gray-800 font-medium">Total Produk</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 80px;">
                                <!-- Stylized orange shoe icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500">
                                    <path d="M4 11c0 0 0.5-8 9-8s8.5 10 8.5 10l1.5 1v4c0 1.5-1 2-2 2h-17c-1 0-2-0.5-2-2v-4l2-3z"/>
                                    <rect x="2" y="14" width="20" height="2" fill="#FF4500"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold">{{ $totalProducts }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Transactions Card -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2">
                            <h3 class="text-gray-800 font-medium">Transaksi Hari Ini</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 80px;">
                                <!-- Stylized orange cart icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500">
                                    <circle cx="9" cy="21" r="1.5"/>
                                    <circle cx="15" cy="21" r="1.5"/>
                                    <path d="M2 2h4l2 12h12l2-12h-20z"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold">{{ $totalTransactions }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales Card -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2">
                            <h3 class="text-gray-800 font-medium">Penjualan Hari Ini</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 80px;">
                                <!-- Stylized orange money icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M6 8v-2m12 2v-2m0 12v2m-12-2v2"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pie Chart for Top Products -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-3">Produk Terlaris</h3>
                    <div class="w-full h-64">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
                
                <!-- Line Chart for Hourly Transactions -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-1">Transaksi Harian</h3>
                    <p class="text-xs text-gray-400 anuncioumb-4">Laporan Transaksi Per Jam Hari Ini</p>
                    <div class="w-full h-64">
                        <canvas id="hourlyTransactionsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Transactions Detail Section -->
            <div class="rounded-lg overflow-hidden border border-blue-100 mb-8">
                <h3 class="text-xl font-semibold p-4 bg-white">Detail Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">ID</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Tanggal</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Customer</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Total</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">{{ $transaction->user->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">Selesai</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600 text-center">Tidak ada transaksi hari ini</td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pie Chart for Top Products
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'pie',
            data: {
                labels: [@foreach($topProducts as $product)"{{ $product['name'] }}",@endforeach],
                datasets: [{
                    data: [@foreach($topProducts as $product){{ $product['quantity'] }},@endforeach],
                    backgroundColor: ['#F97316', '#F5F5F4', '#2563EB', '#16A34A', '#EC4899'],
                    borderColor: '#1F2937',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#D1D5DB'
                        }
                    }
                }
            }
        });

        // Line Chart for Hourly Transactions
        const hourlyTransactionsCtx = document.getElementById('hourlyTransactionsChart').getContext('2d');
        new Chart(hourlyTransactionsCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Transaksi',
                    data: @json($hourlyData),
                    fill: true,
                    backgroundColor: 'rgba(249, 115, 22, 0.8)',
                    borderColor: '#C05621',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#D1D5DB'
                        },
                        grid: {
                            color: '#4B5563'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#D1D5DB'
                        },
                        grid: {
                            color: '#4B5563'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#D1D5DB'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection