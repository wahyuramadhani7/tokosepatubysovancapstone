@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap');
    
    .no-data-message {
        text-align: center;
        color: #D1D5DB;
        padding: 20px;
        animation: fadeIn 0.8s ease-in-out;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Keyframe Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    @keyframes rotateIn {
        from {
            transform: rotate(-200deg);
            opacity: 0;
        }
        to {
            transform: rotate(0deg);
            opacity: 1;
        }
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation Classes */
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-slide-in-left {
        animation: slideInFromLeft 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-slide-in-right {
        animation: slideInFromRight 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-bounce-in {
        animation: bounceIn 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-rotate-in {
        animation: rotateIn 0.6s ease-out forwards;
        opacity: 0;
    }

    /* Hover Effects */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .icon-bounce:hover {
        animation: pulse 0.6s ease-in-out infinite;
    }

    /* Shimmer Effect */
    .shimmer {
        background: linear-gradient(
            110deg,
            transparent 40%,
            rgba(255, 255, 255, 0.2) 50%,
            transparent 60%
        );
        background-size: 200px 100%;
        animation: shimmer 2s infinite linear;
    }

    /* Chart Animation */
    .chart-fade-in {
        animation: fadeIn 1.2s ease-in-out;
    }

    /* Table Row Animation */
    .table-row {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .table-row:nth-child(1) { animation-delay: 0.1s; }
    .table-row:nth-child(2) { animation-delay: 0.2s; }
    .table-row:nth-child(3) { animation-delay: 0.3s; }
    .table-row:nth-child(4) { animation-delay: 0.4s; }
    .table-row:nth-child(5) { animation-delay: 0.5s; }

    /* Hero Animation */
    .hero-content {
        animation: fadeIn 1.5s ease-in-out, pulse 2s ease-in-out infinite;
    }

    /* Number Counter Animation */
    .counter-number {
        animation: countUp 1s ease-out forwards;
        opacity: 0;
    }

    /* Loading Animation for Charts */
    .chart-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 300px;
    }

    .chart-loading::after {
        content: '';
        width: 40px;
        height: 40px;
        border: 4px solid #374151;
        border-top: 4px solid #FF4500;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Staggered Animation Delays */
    .animate-delay-100 { animation-delay: 0.1s; }
    .animate-delay-200 { animation-delay: 0.2s; }
    .animate-delay-300 { animation-delay: 0.3s; }
    .animate-delay-400 { animation-delay: 0.4s; }
    .animate-delay-500 { animation-delay: 0.5s; }
    .animate-delay-600 { animation-delay: 0.6s; }

    /* Smooth transitions for all interactive elements */
    * {
        transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
    }
</style>

<div class="w-full">
    <!-- Hero Section with Background -->
    <div class="relative w-full h-96 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center animate-fade-in-up" style="background-image: url('/images/bgdashboard.png');">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
            <div class="bg-black bg-opacity-80 px-8 py-4 rounded-lg hero-content text-center">
                <h2 class="text-orange-500 font-bold text-2xl whitespace-nowrap" style="font-family: 'Libre Baskerville', serif;">@SEPATUBYSOVAN</h2>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="bg-cover bg-center" style="background-image: url('/images/bgapp.jpg');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-white bg-opacity-95">
            <!-- Daily Report Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-center mb-6 animate-fade-in-up animate-delay-200">LAPORAN HARIAN</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Product Card -->
                    <div class="border border-blue-300 rounded-lg overflow-hidden card-hover animate-slide-in-left animate-delay-300">
                        <div class="bg-blue-50 px-4 py-2 shimmer">
                            <h3 class="text-gray-800 font-medium">Total Produk</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center icon-bounce" style="width: 5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500" class="animate-rotate-in animate-delay-400">
                                    <path d="M4 11c0 0 0.5-8 9-8s8.5 10 8.5 10l1.5 1v4c0 1.5-1 2-2 2h-17c-1 0-2-0.5-2-2v-4l2-3z"/>
                                    <rect x="2" y="14" width="20" height="2" fill="#FF4500"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold counter-number animate-delay-500">{{ $totalProducts }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Transactions Card -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden card-hover animate-bounce-in animate-delay-400">
                        <div class="bg-gray-100 px-4 py-2 shimmer">
                            <h3 class="text-gray-800 font-medium">Transaksi Hari Ini</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center icon-bounce" style="width: 5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500" class="animate-rotate-in animate-delay-500">
                                    <circle cx="9" cy="21" r="1.5"/>
                                    <circle cx="15" cy="21" r="1.5"/>
                                    <path d="M2 2h4l2 12h12l2-12h-20z"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold counter-number animate-delay-600">{{ $totalTransactions }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales Card -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden card-hover animate-slide-in-right animate-delay-500">
                        <div class="bg-gray-100 px-4 py-2 shimmer">
                            <h3 class="text-gray-800 font-medium">Penjualan Hari Ini</h3>
                        </div>
                        <div class="flex">
                            <div class="p-4 bg-white flex items-center justify-center icon-bounce" style="width: 5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#FF4500" class="animate-rotate-in animate-delay-600">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M6 8v-2m12 2v-2m0 12v2m-12-2v2"/>
                                </svg>
                            </div>
                            <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xl font-bold counter-number animate-delay-700">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Section (Diagram Batang untuk Transaksi Harian dan Produk Terlaris) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Diagram Batang untuk Transaksi Harian -->
                <div class="bg-gray-800 rounded-lg p-5 text-white card-hover animate-slide-in-left animate-delay-600">
                    <h3 class="text-lg font-semibold mb-1">Transaksi Harian</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Transaksi Per Jam Hari Ini (hingga {{ now()->format('H:i') }} WIB)</p>
                    @php
                        $currentHour = now()->hour;
                        $hasTransactions = !empty($hourlyData) && array_sum(array_slice($hourlyData, 0, $currentHour + 1)) > 0;
                    @endphp
                    @if(!$hasTransactions)
                        <div class="no-data-message">Tidak ada transaksi hari ini hingga {{ now()->format('H:i') }} WIB</div>
                    @else
                        <div class="chart-container chart-fade-in">
                            <canvas id="hourlyTransactionsChart"></canvas>
                        </div>
                    @endif
                </div>

                <!-- Diagram Batang untuk Produk Terlaris -->
                <div class="bg-gray-800 rounded-lg p-5 text-white card-hover animate-slide-in-right animate-delay-700">
                    <h3 class="text-lg font-semibold mb-1">Produk Terlaris</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Produk Terlaris Bulan Ini ({{ now()->format('F Y') }})</p>
                    @if($topProducts->isEmpty())
                        <div class="no-data-message">Tidak ada data produk terlaris bulan ini</div>
                    @else
                        <div class="chart-container chart-fade-in">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transactions Detail Section -->
            <div class="rounded-lg overflow-hidden border border-blue-100 mb-8 animate-fade-in-up animate-delay-800">
                <h3 class="text-xl font-semibold p-4 bg-white">Detail Transaksi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="animate-fade-in-up animate-delay-900">
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">ID</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Tanggal</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Kasir</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Produk</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Total</th>
                                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $index => $transaction)
                                <tr class="table-row hover:bg-gray-600 transition-all duration-300" style="animation-delay: {{ 1 + ($index * 0.1) }}s">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-row">
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

        // Animasi untuk chart dengan delay
        function animateChart(chartInstance) {
            if (chartInstance) {
                setTimeout(() => {
                    chartInstance.update('active');
                }, 500);
            }
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
                            animation: {
                                duration: 2000,
                                easing: 'easeInOutQuart',
                                delay: function(context) {
                                    return context.dataIndex * 200;
                                }
                            },
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
                    animateChart(hourlyChart);
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
                            animation: {
                                duration: 2000,
                                easing: 'easeInOutBounce',
                                delay: function(context) {
                                    return context.dataIndex * 150;
                                }
                            },
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
                    animateChart(productsChart);
                    console.log('Products chart created successfully');
                } else {
                    console.error('Products canvas element not found');
                }
            } catch (error) {
                console.error('Error creating products chart:', error);
            }
        @endif
        
        console.log('Chart initialization completed');

        // Animasi number counter
        function animateCounters() {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    if (counter.textContent.includes('Rp')) {
                        counter.textContent = 'Rp ' + Math.floor(current).toLocaleString('id-ID');
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 20);
            });
        }

        // Mulai animasi counter setelah delay
        setTimeout(animateCounters, 1000);
    });
</script>

@endsection