@extends('layouts.app')

@section('content')
<div class="w-full bg-white">
    <!-- Hero Section with Background -->
    <div class="relative w-full h-48 bg-gray-200 overflow-hidden">
        <!-- Background image will be replaced by you -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200">
            <!-- You'll replace this with your actual background image -->
        </div>
        
        <!-- Brand Tag -->
        <div class="absolute left-10 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-80 px-5 py-2 rounded-lg">
            <h2 class="text-orange-500 font-bold text-lg">@SEPATUBYSOVAN</h2>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Daily Report Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-center mb-6">LAPORAN HARIAN</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Product Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center shadow-sm">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-md flex items-center justify-center">
                            <!-- Shoe icon placeholder -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-700 mb-1">Total Produk</h3>
                        <div class="bg-gray-800 text-white px-4 py-1 rounded font-bold text-xl">--</div>
                    </div>
                </div>
                
                <!-- Visitors Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center shadow-sm">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-md flex items-center justify-center">
                            <!-- User icon placeholder -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-700 mb-1">Pengunjung Hari Ini</h3>
                        <div class="bg-gray-800 text-white px-4 py-1 rounded font-bold text-xl">--</div>
                    </div>
                </div>
                
                <!-- Stock Card -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center shadow-sm">
                    <div class="mr-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-md flex items-center justify-center">
                            <!-- Stock icon placeholder -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-700 mb-1">Total Stok</h3>
                        <div class="bg-gray-800 text-white px-4 py-1 rounded font-bold text-xl">--</div>
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
                        <!-- Placeholder for pie chart -->
                        <div class="relative w-40 h-40">
                            <div class="absolute inset-0 rounded-full border-8 border-orange-500" style="clip-path: polygon(50% 50%, 100% 0, 100% 100%)"></div>
                            <div class="absolute inset-0 rounded-full border-8 border-blue-500" style="clip-path: polygon(50% 50%, 0 0, 50% 0)"></div>
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
                <div class="w-full h-64 bg-gray-700 rounded-lg">
                    <!-- Placeholder for line chart -->
                    <div class="w-full h-full flex items-end justify-between px-2">
                        <div class="w-1/7 h-1/3 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-1/5 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-2/3 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-4/5 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-3/5 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-1/4 bg-orange-500 opacity-80 rounded-t"></div>
                        <div class="w-1/7 h-1/2 bg-orange-500 opacity-80 rounded-t"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Detail Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-5 mb-8">
            <h3 class="text-xl font-semibold mb-4">Detail Transaksi</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 bg-gray-100 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Empty rows for demonstration -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Placeholder for chart libraries
    // You would need to include Chart.js or another charting library
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts here
        console.log('Dashboard loaded. Charts should be initialized here.');
        
        // You would initialize your actual charts here
        // Example with Chart.js (you'll need to include the library):
        /*
        const productsPieChart = new Chart(
            document.getElementById('productsPieChart'),
            {
                type: 'pie',
                data: {
                    labels: ['Product A', 'Product B', 'Product C'],
                    datasets: [{
                        data: [65, 15, 20],
                        backgroundColor: ['#FF5722', '#2563EB', '#22C55E']
                    }]
                }
            }
        );
        
        const visitorsChart = new Chart(
            document.getElementById('visitorsChart'),
            {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Pengunjung',
                        data: [30, 20, 60, 80, 65, 25, 50],
                        fill: true,
                        backgroundColor: 'rgba(255, 87, 34, 0.5)',
                        borderColor: '#FF5722'
                    }]
                }
            }
        );
        */
    });
</script>
@endpush
@endsection