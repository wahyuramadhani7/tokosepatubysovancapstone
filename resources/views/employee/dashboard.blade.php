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
            <!-- You can replace '/images/shoes-background.jpg' with your actual image path -->
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
                
           <!-- Daily Report Section -->
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <span class="text-white text-xl font-bold">--</span>
                </div>
            </div>
        </div>
        
        <!-- Visitors Card -->
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gray-100 px-4 py-2">
                <h3 class="text-gray-800 font-medium">Pengunjung Hari Ini</h3>
            </div>
            <div class="flex">
                <div class="p-4 bg-white flex items-center justify-center" style="width: 80px;">
                    <!-- Stylized orange people icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                        <circle cx="9" cy="8" r="4" fill="#FF4500"/>
                        <circle cx="15" cy="8" r="4" fill="#FF4500"/>
                        <circle cx="12" cy="6" r="3" fill="#FF4500"/>
                        <path d="M2 18 C2 16 7 14 12 14 S22 16 22 18 V22 H2 V18" fill="#FF4500"/>
                    </svg>
                </div>
                <div class="flex-grow p-4 bg-gray-800 flex items-center justify-center">
                    <span class="text-white text-xl font-bold">--</span>
                </div>
            </div>
        </div>
    </div>
</div>
           <!-- Charts Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Pie Chart -->
    <div class="bg-gray-800 rounded-lg p-5 text-white">
        <h3 class="text-lg font-semibold mb-3">Produk Terlaris</h3>
        <div class="aspect-w-1 aspect-h-1 bg-gray-700 rounded-lg mb-4">
            <!-- Replace with actual chart -->
            <div class="w-full h-64 flex items-center justify-center">
                <!-- Pie chart matching the image -->
                <div class="relative w-40 h-40">
                    <!-- Orange section (largest) -->
                    <div class="absolute inset-0 rounded-full bg-orange-600" style="clip-path: polygon(50% 50%, 0 0, 0 100%, 50% 100%)"></div>
                    <!-- Cream/white section -->
                    <div class="absolute inset-0 rounded-full bg-orange-50" style="clip-path: polygon(50% 50%, 0 0, 50% 0, 100% 0, 100% 50%)"></div>
                    <!-- Blue section -->
                    <div class="absolute inset-0 rounded-full bg-blue-600" style="clip-path: polygon(50% 50%, 100% 50%, 100% 100%)"></div>
                    <!-- Center circle -->
                    <div class="absolute w-4 h-4 rounded-full bg-gray-900" style="top: calc(50% - 8px); left: calc(50% - 8px);"></div>
                </div>
            </div>
        </div>
        <div class="space-y-2">
            <div class="h-2 bg-gray-600 rounded"></div>
            <div class="h-2 bg-gray-600 rounded"></div>
            <div class="h-2 bg-gray-600 rounded"></div>
        </div>
    </div>
    
    <!-- Line Chart -->
    <div class="bg-gray-800 rounded-lg p-5 text-white">
        <h3 class="text-lg font-semibold mb-1">Grafik Pengunjung Mingguan</h3>
        <p class="text-xs text-gray-400 mb-4">Laporan Pengunjung Selama Seminggu</p>
        <div class="w-full h-64 bg-gray-800 rounded-lg relative">
            <!-- Grid lines -->
            <div class="absolute inset-0 grid grid-cols-7 grid-rows-4">
                <!-- Vertical grid lines -->
                <div class="border-r border-gray-700 h-full"></div>
                <div class="border-r border-gray-700 h-full"></div>
                <div class="border-r border-gray-700 h-full"></div>
                <div class="border-r border-gray-700 h-full"></div>
                <div class="border-r border-gray-700 h-full"></div>
                <div class="border-r border-gray-700 h-full"></div>
                <!-- Horizontal grid lines -->
                <div class="col-span-7 border-t border-gray-700 w-full absolute top-1/4"></div>
                <div class="col-span-7 border-t border-gray-700 w-full absolute top-2/4"></div>
                <div class="col-span-7 border-t border-gray-700 w-full absolute top-3/4"></div>
                <div class="col-span-7 border-t border-gray-700 w-full absolute bottom-0"></div>
            </div>
            
            <!-- Area chart matching the image -->
            <div class="absolute inset-0 flex items-end">
                <svg viewBox="0 0 700 300" class="w-full h-full">
                    <!-- Area chart path -->
                    <path d="M0,150 L100,225 L200,75 L300,50 L400,75 L500,225 L600,100 L700,150 L700,300 L0,300 Z" 
                          fill="#C05621" fill-opacity="0.8" />
                </svg>
            </div>
            
            <!-- Day labels -->
            <div class="absolute bottom-0 w-full flex justify-between px-2 text-xs text-gray-400">
                <div>SENIN</div>
                <div>SELASA</div>
                <div>RABU</div>
                <div>KAMIS</div>
                <div>JUMAT</div>
                <div>SABTU</div>
                <div>MINGGU</div>
            </div>
            
            <!-- Y-axis labels -->
            <div class="absolute left-0 h-full flex flex-col justify-between py-1 text-xs text-gray-400">
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
<div class="rounded-lg overflow-hidden border border-blue-100 mb-8">
    <h3 class="text-xl font-semibold p-4 bg-white">Detail Transaksi</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">ID</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Tanggal</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Customer</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Produk</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Harga</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Status</th>
                    <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider border border-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Empty rows for demonstration with dark styling -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 bg-gray-700 border border-gray-600">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts here
        console.log('Dashboard loaded. Charts should be initialized here.');
        
    });
</script>
@endpush
@endsection