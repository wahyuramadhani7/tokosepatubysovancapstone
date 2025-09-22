@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-lg sm:text-xl md:text-2xl font-bold mb-4 sm:mb-6 bg-orange-500 text-white py-2 px-4 rounded inline-block">RIWAYAT PRODUK</h1>

        <!-- Filters -->
        <div class="mb-6">
            <form action="{{ route('inventory.history') }}" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                    <label for="time_filter" class="text-sm sm:text-base font-medium text-gray-700">Filter Waktu:</label>
                    <select name="time_filter" id="time_filter" class="bg-white text-black text-sm sm:text-base rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 w-full sm:w-auto">
                        <option value="all" {{ $time_filter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="weekly" {{ $time_filter === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $time_filter === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                    <label for="action_filter" class="text-sm sm:text-base font-medium text-gray-700">Filter Aksi:</label>
                    <select name="action_filter" id="action_filter" class="bg-white text-black text-sm sm:text-base rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 w-full sm:w-auto">
                        <option value="all" {{ $action_filter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="added" {{ $action_filter === 'added' ? 'selected' : '' }}>Ditambahkan</option>
                        <option value="edited" {{ $action_filter === 'edited' ? 'selected' : '' }}>Diperbarui</option>
                        <option value="deleted" {{ $action_filter === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                    </select>
                </div>
                <button type="submit" class="bg-orange-500 text-white font-medium text-sm sm:text-base rounded-lg px-4 py-2 hover:bg-orange-600 transition-colors w-full sm:w-auto">Filter</button>
            </form>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()" aria-label="Close alert">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()" aria-label="Close alert">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="hidden md:block shadow rounded-lg overflow-x-auto" style="background-color: #292929;">
            <div class="min-w-[1000px]">
                <!-- Table Headers -->
                <div class="grid grid-cols-10 gap-0">
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">No</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Aksi</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Brand</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Model</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Ukuran</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Warna</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Stok</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Perubahan Stok</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">User</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm sm:text-base">Tanggal</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1">
                    @forelse ($filteredHistory as $index => $item)
                        <div class="grid grid-cols-10 gap-0 items-center {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ $index + 1 }}</div>
                            <div class="p-3 text-black text-center uppercase-text text-sm sm:text-base">
                                @if($item['type'] === 'added')
                                    Ditambahkan
                                @elseif($item['type'] === 'edited')
                                    Diperbarui
                                @else
                                    Dihapus
                                @endif
                            </div>
                            <div class="p-3 text-black text-center uppercase-text text-sm sm:text-base">{{ $item['brand'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text text-sm sm:text-base">{{ $item['model'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ $item['size'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text text-sm sm:text-base">{{ $item['color'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ $item['stock'] ?? 0 }}</div>
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ $item['stock_change'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ $item['user_name'] ?? 'Unknown' }}</div>
                            <div class="p-3 text-black text-center text-sm sm:text-base">{{ \Carbon\Carbon::parse($item['timestamp'])->format('d-m-Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="bg-white p-6 text-center text-gray-500 text-sm sm:text-base">
                            Tidak ada riwayat produk ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse ($filteredHistory as $index => $item)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="space-y-2">
                        <h3 class="font-medium text-gray-900 text-sm sm:text-base">No: {{ $index + 1 }}</h3>
                        <div class="text-sm text-gray-600 uppercase-text">Aksi: 
                            @if($item['type'] === 'added')
                                Ditambahkan
                            @elseif($item['type'] === 'edited')
                                Diperbarui
                            @else
                                Dihapus
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 uppercase-text">Brand: {{ $item['brand'] ?? '-' }}</div>
                        <div class="text-sm text-gray-600 uppercase-text">Model: {{ $item['model'] ?? '-' }}</div>
                        <div class="text-sm text-gray-600">Ukuran: {{ $item['size'] ?? '-' }}</div>
                        <div class="text-sm text-gray-600 uppercase-text">Warna: {{ $item['color'] ?? '-' }}</div>
                        <div class="text-sm text-gray-600">Stok: {{ $item['stock'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Perubahan Stok: {{ $item['stock_change'] ?? '-' }}</div>
                        <div class="text-sm text-gray-600">User: {{ $item['user_name'] ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($item['timestamp'])->format('d-m-Y H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-4 text-sm sm:text-base">
                    Tidak ada riwayat produk ditemukan.
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    .grid-cols-10 {
        grid-template-columns: repeat(10, minmax(0, 1fr));
    }
    .uppercase-text {
        text-transform: uppercase;
    }
    /* Ensure table is scrollable on smaller screens */
    @media (min-width: 768px) {
        .grid-cols-10 {
            grid-template-columns: 1fr 1.5fr 2fr 2fr 1.5fr 1.5fr 1fr 1.5fr 2fr 2fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection