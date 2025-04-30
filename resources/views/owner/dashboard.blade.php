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
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px; md:width: 80px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10">
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
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px; md:width: 80px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10">
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
                            <div class="p-2 md:p-4 bg-white flex items-center justify-center" style="width: 60px; md:width: 80px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="#FF4500" class="w-6 h-6 md:w-8 md:h-8 lg:w-10 lg:h-10">
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
                <!-- Pie Chart -->
                <div class="bg-gray-800 rounded-lg p-3 md:p-5 text-white">
                    <h3 class="text-base md:text-lg font-semibold mb-2 md:mb-3">Produk Terlaris</h3>
                    <div class="aspect-w-1 aspect-h-1 bg-gray-700 rounded-lg mb-3 md:mb-4">
                        <div class="w-full h-48 md:h-56 lg:h-64 flex items-center justify-center">
                            <div class="relative w-32 h-32 md:w-40 md:h-40">
                                <div class="absolute inset-0 rounded-full bg-orange-600" style="clip-path: polygon(50% 50%, 0 0, 0 100%, 50% 100%)"></div>
                                <div class="absolute inset-0 rounded-full bg-orange-50" style="clip-path: polygon(50% 50%, 0 0, 50% 0, 100% 0, 100% 50%)"></div>
                                <div class="absolute inset-0 rounded-full bg-blue-600" style="clip-path: polygon(50% 50%, 100% 50%, 100% 100%)"></div>
                                <div class="absolute w-3 h-3 md:w-4 md:h-4 rounded-full bg-gray-900" style="top: calc(50% - 6px); left: calc(50% - 6px); md:top: calc(50% - 8px); md:left: calc(50% - 8px);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1 md:space-y-2">
                        <div class="h-1.5 md:h-2 bg-gray-600 rounded"></div>
                        <div class="h-1.5 md:h-2 bg-gray-600 rounded"></div>
                        <div class="h-1.5 md:h-2 bg-gray-600 rounded"></div>
                    </div>
                </div>

                <!-- Line Chart -->
                <div class="bg-gray-800 rounded-lg p-3 md:p-5 text-white">
                    <h3 class="text-base md:text-lg font-semibold mb-0.5 md:mb-1">Grafik Transaksi Mingguan</h3>
                    <p class="text-2xs md:text-xs text-gray-400 mb-2 md:mb-4">Laporan Transaksi Selama Seminggu</p>
                    <div class="w-full h-48 md:h-56 lg:h-64 bg-gray-800 rounded-lg relative">
                        <div class="absolute inset-0 grid grid-cols-7 grid-rows-4">
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="border-r border-gray-700 h-full"></div>
                            <div class="col-span-7 border-t border-gray-700 w-full absolute top-1/4"></div>
                            <div class="col-span-7 border-t border-gray-700 w-full absolute top-2/4"></div>
                            <div class="col-span-7 border-t border-gray-700 w-full absolute top-3/4"></div>
                            <div class="col-span-7 border-t border-gray-700 w-full absolute bottom-0"></div>
                        </div>
                        <div class="absolute inset-0 flex items-end">
                            <svg viewBox="0 0 700 300" class="w-full h-full">
                                <path d="M0,150 L100,225 L200,75 L300,50 L400,75 L500,225 L600,100 L700,150 L700,300 L0,300 Z" 
                                      fill="#C05621" fill-opacity="0.8" />
                            </svg>
                        </div>
                        <div class="absolute bottom-0 w-full flex justify-between px-1 md:px-2 text-3xs sm:text-2xs md:text-xs text-gray-400">
                            <div>SEN</div>
                            <div>SEL</div>
                            <div>RAB</div>
                            <div>KAM</div>
                            <div>JUM</div>
                            <div>SAB</div>
                            <div>MIN</div>
                        </div>
                        <div class="absolute left-0 h-full flex flex-col justify-between py-1 text-3xs sm:text-2xs md:text-xs text-gray-400">
                            <div>100</div>
                            <div>75</div>
                            <div>50</div>
                            <div>25</div>
                            <div>0</div>
                        </div>
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
        console.log('Dashboard loaded. Charts should be initialized here.');
        // Responsive adjustments for mobile devices
        const adjustForScreenSize = () => {
            // Add any JavaScript-based responsive adjustments here if needed
        };
        
        // Initial adjustment
        adjustForScreenSize();
        
        // Adjust on resize
        window.addEventListener('resize', adjustForScreenSize);
    });
</script>
@endpush
@endsection