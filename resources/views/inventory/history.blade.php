@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-6 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-lg sm:text-xl md:text-2xl font-bold mb-4 sm:mb-6 bg-orange-500 text-white py-2 px-4 rounded inline-block">RIWAYAT PRODUK</h1>

        <!-- Filters -->
        <div class="mb-6">
            <form action="{{ route('inventory.history') }}" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                    <label for="search" class="text-sm sm:text-base font-medium text-gray-700">Cari:</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari brand, model..." class="bg-white text-black text-sm sm:text-base rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 w-full sm:w-auto">
                </div>
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
                        <option value="masuk" {{ $action_filter === 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="perbarui" {{ $action_filter === 'perbarui' ? 'selected' : '' }}>Barang Diperbarui</option>
                        <option value="keluar" {{ $action_filter === 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
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

        <!-- Table for All Devices -->
        <div class="shadow rounded-lg overflow-x-auto bg-gray-800">
            <div class="min-w-[1000px]">
                <!-- Table Headers -->
                <div class="grid grid-cols-10 gap-0 sticky top-0 z-10">
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">No</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Aksi</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Brand</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Model</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Ukuran</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Warna</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Stok</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Perubahan Stok</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">User</div>
                    <div class="bg-orange-500 text-white font-medium py-2 px-3 text-center text-sm">Tanggal</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1">
                    @forelse ($filteredHistory as $index => $item)
                        <div class="grid grid-cols-10 gap-0 items-center {{ $index % 2 == 0 ? 'bg-gray-700' : 'bg-gray-600' }}">
                            <div class="p-3 text-white text-center text-sm">{{ $index + 1 }}</div>
                            <div class="p-3 text-white text-center uppercase-text text-sm">
                                @if($item->type === 'masuk')
                                    Barang Masuk
                                @elseif($item->type === 'perbarui')
                                    Barang Diperbarui
                                @else
                                    Barang Keluar
                                @endif
                            </div>
                            <div class="p-3 text-white text-center uppercase-text text-sm">{{ $item->brand ?? '-' }}</div>
                            <div class="p-3 text-white text-center uppercase-text text-sm">{{ $item->model ?? '-' }}</div>
                            <div class="p-3 text-white text-center text-sm">{{ $item->size ?? '-' }}</div>
                            <div class="p-3 text-white text-center uppercase-text text-sm">{{ $item->color ?? '-' }}</div>
                            <div class="p-3 text-white text-center text-sm">{{ $item->stock ?? 0 }}</div>
                            <div class="p-3 text-white text-center text-sm">{{ $item->stock_change ?? '-' }}</div>
                            <div class="p-3 text-white text-center text-sm">{{ $item->user_name ?? 'Unknown' }}</div>
                            <div class="p-3 text-white text-center text-sm">{{ \Carbon\Carbon::parse($item->timestamp)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="bg-gray-700 p-6 text-center text-gray-300 text-sm">
                            Tidak ada riwayat produk ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
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
    /* Ensure table is scrollable on all screens */
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: auto;
        scrollbar-width: auto;
        scrollbar-color: #f97316 #1f2937;
    }
    .overflow-x-auto::-webkit-scrollbar {
        height: 12px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #1f2937;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #f97316;
        border-radius: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #ea580c;
    }
    @media (min-width: 768px) {
        .grid-cols-10 {
            grid-template-columns: 1fr 1.5fr 2fr 2fr 1.5fr 1.5fr 1fr 1.5fr 2fr 2fr;
        }
        .grid-cols-10 > div {
            font-size: 1rem;
        }
    }
    @media (max-width: 767px) {
        .grid-cols-10 {
            grid-template-columns: repeat(10, minmax(100px, 1fr));
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