@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">RIWAYAT PRODUK</h1>

        <!-- Filters -->
        <div class="mb-6">
            <form action="{{ route('inventory.history') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="time_filter" class="text-sm md:text-base font-medium text-gray-700">Filter Waktu:</label>
                    <select name="time_filter" id="time_filter" class="bg-white text-black text-sm md:text-base rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all" {{ $time_filter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="weekly" {{ $time_filter === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $time_filter === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label for="action_filter" class="text-sm md:text-base font-medium text-gray-700">Filter Aksi:</label>
                    <select name="action_filter" id="action_filter" class="bg-white text-black text-sm md:text-base rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="all" {{ $action_filter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="added" {{ $action_filter === 'added' ? 'selected' : '' }}>Ditambahkan</option>
                        <option value="edited" {{ $action_filter === 'edited' ? 'selected' : '' }}>Diperbarui</option>
                        <option value="deleted" {{ $action_filter === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                    </select>
                </div>
                <button type="submit" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2 hover:bg-orange-600 transition-colors">Filter</button>
            </form>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="shadow rounded-lg overflow-hidden hidden md:block p-4" style="background-color: #292929;">
            <div class="rounded-lg overflow-hidden">
                <!-- Table Headers -->
                <div class="grid grid-cols-10 gap-0">
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">No</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Aksi</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Brand</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Model</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Ukuran</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Warna</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Stok</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Perubahan Stok</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">User</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Tanggal</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1">
                    @forelse ($filteredHistory as $index => $item)
                        <div class="grid grid-cols-10 gap-0 items-center {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                            <div class="p-3 text-black text-center">{{ $index + 1 }}</div>
                            <div class="p-3 text-black text-center uppercase-text">
                                @if($item['type'] === 'added')
                                    Ditambahkan
                                @elseif($item['type'] === 'edited')
                                    Diperbarui
                                @else
                                    Dihapus
                                @endif
                            </div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item['brand'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item['model'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $item['size'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item['color'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $item['stock'] ?? 0 }}</div>
                            <div class="p-3 text-black text-center">{{ $item['stock_change'] ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $item['user_name'] ?? 'Unknown' }}</div>
                            <div class="p-3 text-black text-center">{{ \Carbon\Carbon::parse($item['timestamp'])->format('d-m-Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="bg-white p-6 text-center text-gray-500">
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
                    <div class="mb-2">
                        <h3 class="font-medium text-gray-900">No: {{ $index + 1 }}</h3>
                        <div class="text-sm text-gray-500 uppercase-text">
                            Aksi: 
                            @if($item['type'] === 'added')
                                Ditambahkan
                            @elseif($item['type'] === 'edited')
                                Diperbarui
                            @else
                                Dihapus
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 uppercase-text">Brand: {{ $item['brand'] ?? '-' }}</div>
                        <div class="text-sm text-gray-500 uppercase-text">Model: {{ $item['model'] ?? '-' }}</div>
                        <div class="text-sm text-gray-500">Ukuran: {{ $item['size'] ?? '-' }}</div>
                        <div class="text-sm text-gray-500 uppercase-text">Warna: {{ $item['color'] ?? '-' }}</div>
                        <div class="text-sm text-gray-500">Stok: {{ $item['stock'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Perubahan Stok: {{ $item['stock_change'] ?? '-' }}</div>
                        <div class="text-sm text-gray-500">User: {{ $item['user_name'] ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($item['timestamp'])->format('d-m-Y H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-4">
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
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