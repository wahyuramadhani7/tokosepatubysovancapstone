@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap');
    .no-data-message {
        text-align: center;
        color: #D1D5DB;
        padding: 20px;
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
                            <div class="p-4 bg-white flex items-center justify-center" style="width: 80px;">
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

            <!-- Data Section (Tabel untuk Transaksi Harian dan Produk Terlaris) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tabel untuk Transaksi Harian -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-1">Transaksi Harian</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Transaksi Per Jam Hari Ini (hingga {{ now()->format('H:i') }} WIB)</p>
                    @php
                        $currentHour = now()->hour; // Dapatkan jam saat ini (misalnya, 21 untuk 21:00)
                    @endphp
                    @if(empty($hourlyData) || array_sum(array_slice($hourlyData, 0, $currentHour + 1)) == 0)
                        <div class="no-data-message">Tidak ada transaksi hari ini hingga {{ now()->format('H:i') }} WIB</div>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jumlah Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($labels, 0, $currentHour + 1) as $index => $label)
                                    @if($hourlyData[$index] > 0) <!-- Hanya tampilkan jam dengan transaksi -->
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-300 bg-gray-800">{{ $label }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-300 bg-gray-800">{{ $hourlyData[$index] }} transaksi</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Tabel untuk Produk Terlaris -->
                <div class="bg-gray-800 rounded-lg p-5 text-white">
                    <h3 class="text-lg font-semibold mb-1">Produk Terlaris</h3>
                    <p class="text-xs text-gray-400 mb-4">Laporan Produk Terlaris Hari Ini</p>
                    @if($topProducts->isEmpty())
                        <div class="no-data-message">Tidak ada data produk terlaris hari ini</div>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kuantitas Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-300 bg-gray-800">{{ $product['name'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-300 bg-gray-800">{{ $product['quantity'] }} unit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
<!-- Tidak ada script karena tidak menggunakan Chart.js -->
@endpush
@endsection