@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap');
    .no-data-message {
        text-align: center;
        color: #D1D5DB;
        padding: 20px;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
<div class="w-full">
    <!-- Hero Section with Background -->
    <div class="relative w-full h-96 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/bgdashboard.png');">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
        <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black bg-opacity-80 px-8 py-4 rounded-lg z-10">
            <h2 class="text-orange-500 font-bold text-2xl" style="font-family: 'Libre Baskerville', serif;">@SEPATUBYSOVAN</h2>
        </div>
    </div>

    <!-- Main Dashboard Content -->
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
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 5rem;">
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
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 5rem;">
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
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 5rem;">
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

            <!-- Data Section (Diagram Batang untuk Transaksi Harian dan Produk Terlaris) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Diagram Batang untuk Transaksi Harian -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-1">Transaksi Harian</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Transaksi Per Jam Hari Ini (hingga {{ now()->format('H:i') }} WIB)</p>
                    @php
                        $currentHour = now()->hour;
                        $hasTransactions = !empty($hourlyData) && array_sum(array_slice($hourlyData, 0, $currentHour + 1)) > 0;
                    @endphp
                    @if(!$hasTransactions)
                        <div class="no-data-message">Tidak ada transaksi hari ini hingga {{ now()->format('H:i') }} WIB</div>
                    @else
                        <div class="chart-container">
                            <canvas id="hourlyTransactionsChart"></canvas>
                        </div>
                    @endif
                </div>

                <!-- Diagram Batang untuk Produk Terlaris -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-1">Produk Terlaris</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Produk Terlaris Bulan Ini ({{ now()->format('F Y') }})</p>
                    @if($topProducts->isEmpty())
                        <div class="no-data-message">Tidak ada data produk terlaris bulan ini</div>
                    @else
                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    @endif
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
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Kasir</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Produk</th>
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
                                    <td class="px-6 py-4 text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        @if($transaction->items->isEmpty())
                                            -
                                        @else
                                            {{ $transaction->items->map(fn($item) => $item->product->name ?? '-')->implode(', ') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">Selesai</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600 text-center">Tidak ada transaksi hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inline Script untuk memastikan dimuat -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Debug: Cek apakah Chart.js berhasil dimuat
    console.log('Chart.js loaded:', typeof Chart !== 'undefined');
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, starting chart initialization...');
        
        // Debug: Cek elemen canvas ada atau tidak
        const hourlyCanvas = document.getElementById('hourlyTransactionsChart');
        const productsCanvas = document.getElementById('topProductsChart');
        console.log('Hourly canvas found:', hourlyCanvas !== null);
        console.log('Products canvas found:', productsCanvas !== null);

        // Konfigurasi untuk Chart.js dengan tema gelap
        if (typeof Chart !== 'undefined') {
            Chart.defaults.color = '#D1D5DB';
            Chart.defaults.borderColor = '#374151';
            Chart.defaults.backgroundColor = '#374151';
        }

        // Data untuk debugging
        @php
            $currentHour = now()->hour;
            $hasTransactions = !empty($hourlyData) && array_sum(array_slice($hourlyData, 0, $currentHour + 1)) > 0;
        @endphp

        console.log('Has transactions:', {{ $hasTransactions ? 'true' : 'false' }});
        console.log('Top products count:', {{ $topProducts->count() ?? 0 }});

        // Fungsi untuk menghasilkan warna random yang cerah
        function generateColors(count) {
            const colors = [
                '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
                '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
                '#F8C471', '#82E0AA', '#F9CA24', '#F0932B', '#EB4D4B',
                '#6C5CE7', '#A29BFE', '#FD79A8', '#FDCB6E', '#E17055',
                '#00B894', '#00CEC9', '#6C5CE7', '#A29BFE', '#FD79A8'
            ];
            
            const result = [];
            for (let i = 0; i < count; i++) {
                result.push(colors[i % colors.length]);
            }
            return result;
        }

        // Chart untuk Transaksi Harian
        @if($hasTransactions)
            try {
                const hourlyCtx = document.getElementById('hourlyTransactionsChart');
                if (hourlyCtx) {
                    const hourlyLabels = @json(array_slice($labels ?? [], 0, $currentHour + 1));
                    const hourlyDataValues = @json(array_slice($hourlyData ?? [], 0, $currentHour + 1));
                    
                    console.log('Hourly labels:', hourlyLabels);
                    console.log('Hourly data:', hourlyDataValues);
                    
                    // Filter data yang memiliki transaksi > 0
                    const filteredHourlyData = [];
                    const filteredHourlyLabels = [];
                    for (let i = 0; i < hourlyDataValues.length; i++) {
                        if (hourlyDataValues[i] > 0) {
                            filteredHourlyData.push(hourlyDataValues[i]);
                            filteredHourlyLabels.push(hourlyLabels[i]);
                        }
                    }

                    console.log('Filtered hourly data:', filteredHourlyData);
                    console.log('Filtered hourly labels:', filteredHourlyLabels);

                    // Generate warna untuk setiap batang
                    const hourlyColors = generateColors(filteredHourlyData.length);

                    const hourlyChart = new Chart(hourlyCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: filteredHourlyLabels,
                            datasets: [{
                                label: 'Jumlah Transaksi',
                                data: filteredHourlyData,
                                backgroundColor: hourlyColors,
                                borderColor: hourlyColors.map(color => color + 'CC'), // Tambahkan transparansi untuk border
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#D1D5DB'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#D1D5DB',
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: '#374151'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#D1D5DB'
                                    },
                                    grid: {
                                        color: '#374151'
                                    }
                                }
                            }
                        }
                    });
                    console.log('Hourly chart created successfully');
                } else {
                    console.error('Hourly canvas element not found');
                }
            } catch (error) {
                console.error('Error creating hourly chart:', error);
            }
        @endif

        // Chart untuk Produk Terlaris
        @if(!$topProducts->isEmpty())
            try {
                const productsCtx = document.getElementById('topProductsChart');
                if (productsCtx) {
                    const productLabels = @json($topProducts->pluck('name')->toArray());
                    const productData = @json($topProducts->pluck('quantity')->toArray());

                    console.log('Product labels:', productLabels);
                    console.log('Product data:', productData);

                    // Generate warna untuk setiap produk
                    const productColors = generateColors(productData.length);

                    const productsChart = new Chart(productsCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: productLabels,
                            datasets: [{
                                label: 'Kuantitas Terjual',
                                data: productData,
                                backgroundColor: productColors,
                                borderColor: productColors.map(color => color + 'CC'), // Tambahkan transparansi untuk border
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#D1D5DB'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#D1D5DB',
                                        stepSize: 1
                                    },
                                    grid: {
                                        color: '#374151'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#D1D5DB',
                                        maxRotation: 45,
                                        minRotation: 0
                                    },
                                    grid: {
                                        color: '#374151'
                                    }
                                }
                            }
                        }
                    });
                    console.log('Products chart created successfully');
                } else {
                    console.error('Products canvas element not found');
                }
            } catch (error) {
                console.error('Error creating products chart:', error);
            }
        @endif
        
        console.log('Chart initialization completed');
    });
</script>

@endsection