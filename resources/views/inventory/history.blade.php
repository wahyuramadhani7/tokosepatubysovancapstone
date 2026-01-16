@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header + Tombol Kembali + Per Page Selector -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
            <h1 class="text-xl md:text-2xl font-bold bg-orange-500 text-black py-2 px-4 rounded inline-block">RIWAYAT PRODUK</h1>

            <div class="flex flex-col sm:flex-row gap-2">
                <!-- Tombol Kembali -->
                <a href="{{ route('inventory.index') }}"
                   class="bg-gray-600 text-white font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-gray-700 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>

                <!-- Selector: Tampilkan per halaman -->
                <form method="GET" class="flex items-center gap-1 text-sm">
                    @foreach(['search', 'time_filter', 'action_filter'] as $param)
                        @if(request($param))
                            <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                        @endif
                    @endforeach
                    <select name="per_page" onchange="this.form.submit()"
                            class="bg-white text-gray-900 rounded-lg px-3 py-2 text-sm border focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="10" {{ $perPage == '10' ? 'selected' : '' }}>10 / hal</option>
                        <option value="100" {{ $perPage == '100' ? 'selected' : '' }}>100 / hal</option>
                        <option value="500" {{ $perPage == '500' ? 'selected' : '' }}>500 / hal</option>
                        <option value="1000" {{ $perPage == '1000' ? 'selected' : '' }}>1000 / hal</option>
                        <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-800 p-4 md:p-6 mb-6 rounded-lg shadow-sm">
            <form action="{{ route('inventory.history') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                <div class="relative flex-grow">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="w-full bg-white text-gray-900 text-sm md:text-base rounded-lg py-2.5 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500 border border-gray-300"
                           placeholder="Cari brand, model...">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="flex-grow">
                    <select name="time_filter" id="time_filter"
                            class="w-full bg-white text-gray-900 text-sm md:text-base rounded-lg py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 border border-gray-300">
                        <option value="all" {{ $time_filter === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="weekly" {{ $time_filter === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $time_filter === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="flex-grow">
                    <select name="action_filter" id="action_filter"
                            class="w-full bg-white text-gray-900 text-sm md:text-base rounded-lg py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 border border-gray-300">
                        <option value="all" {{ $action_filter === 'all' ? 'selected' : '' }}>Semua Aksi</option>
                        <option value="masuk" {{ $action_filter === 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="perbarui" {{ $action_filter === 'perbarui' ? 'selected' : '' }}>Barang Diperbarui</option>
                        <option value="keluar" {{ $action_filter === 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>

                <button type="submit"
                        class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors w-full md:w-auto">
                    Filter
                </button>
            </form>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="shadow rounded-lg overflow-hidden hidden md:block bg-gray-800 p-4">
            <div class="rounded-lg overflow-hidden">
                <div class="grid grid-cols-10 gap-0 bg-orange-500 text-black font-medium">
                    <div class="py-2 px-3 text-center">No</div>
                    <div class="py-2 px-3 text-center">Aksi</div>
                    <div class="py-2 px-3 text-center">Brand</div>
                    <div class="py-2 px-3 text-center">Model</div>
                    <div class="py-2 px-3 text-center">Ukuran</div>
                    <div class="py-2 px-3 text-center">Warna</div>
                    <div class="py-2 px-3 text-center">Stok</div>
                    <div class="py-2 px-3 text-center">Perubahan</div>
                    <div class="py-2 px-3 text-center">User</div>
                    <div class="py-2 px-3 text-center">Tanggal</div>
                </div>

                <div class="mt-1">
                    @forelse ($filteredHistory as $item)
                        @php
                            $actionText = $item->type === 'masuk' ? 'Barang Masuk' : ($item->type === 'perbarui' ? 'Barang Diperbarui' : 'Barang Keluar');
                            $actionColor = $item->type === 'masuk' ? 'text-green-600' : ($item->type === 'perbarui' ? 'text-blue-600' : 'text-red-600');
                        @endphp
                        <div class="grid grid-cols-10 gap-0 items-center {{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                            <div class="p-3 text-black text-center">{{ $loop->iteration }}</div>
                            <div class="p-3 text-center font-medium {{ $actionColor }}">{{ $actionText }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item->brand ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item->model ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $item->size ?? '-' }}</div>
                            <div class="p-3 text-black text-center uppercase-text">{{ $item->color ?? '-' }}</div>
                            <div class="p-3 text-black text-center font-medium">{{ $item->stock ?? 0 }}</div>
                            <div class="p-3 text-center font-medium {{ $item->stock_change > 0 ? 'text-green-600' : ($item->stock_change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                {{ $item->stock_change > 0 ? '+' . $item->stock_change : $item->stock_change ?? '-' }}
                            </div>
                            <div class="p-3 text-black text-center">{{ $item->user_name ?? 'Unknown' }}</div>
                            <div class="p-3 text-black text-center text-sm">
                                {{ \Carbon\Carbon::parse($item->timestamp)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                            </div>
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
        <div class="md:hidden space-y-5">
            @forelse ($filteredHistory as $item)
                @php
                    $actionText = $item->type === 'masuk' ? 'Barang Masuk' : ($item->type === 'perbarui' ? 'Barang Diperbarui' : 'Barang Keluar');
                    $actionBg   = $item->type === 'masuk' ? 'bg-green-600' : ($item->type === 'perbarui' ? 'bg-blue-600' : 'bg-red-600');
                @endphp
                <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">No: {{ $loop->iteration }}</h3>
                            <span class="inline-block mt-1 {{ $actionBg }} text-white text-xs font-bold px-3 py-1 rounded-full">
                                {{ $actionText }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-base font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($item->timestamp)->setTimezone('Asia/Jakarta')->format('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->timestamp)->setTimezone('Asia/Jakarta')->format('H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-base">
                        <div><div class="text-gray-700 font-medium">Brand</div><div class="font-semibold text-gray-900 uppercase-text">{{ $item->brand ?? '-' }}</div></div>
                        <div><div class="text-gray-700 font-medium">Model</div><div class="font-semibold text-gray-900 uppercase-text">{{ $item->model ?? '-' }}</div></div>
                        <div><div class="text-gray-700 font-medium">Ukuran</div><div class="font-semibold text-gray-900">{{ $item->size ?? '-' }}</div></div>
                        <div><div class="text-gray-700 font-medium">Warna</div><div class="font-semibold text-gray-900 uppercase-text">{{ $item->color ?? '-' }}</div></div>
                        <div><div class="text-gray-700 font-medium">Stok</div><div class="font-semibold text-gray-900">{{ $item->stock ?? 0 }}</div></div>
                        <div>
                            <div class="text-gray-700 font-medium">Perubahan</div>
                            <div class="font-semibold {{ $item->stock_change > 0 ? 'text-green-600' : ($item->stock_change < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                {{ $item->stock_change > 0 ? '+' . $item->stock_change : $item->stock_change ?? '-' }}
                            </div>
                        </div>
                        <div class="col-span-2"><div class="text-gray-700 font-medium">User</div><div class="font-semibold text-gray-900">{{ $item->user_name ?? 'Unknown' }}</div></div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-6 text-center text-gray-600 font-medium">
                    Tidak ada riwayat produk ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination (hanya jika bukan 'all') -->
        @if($filteredHistory instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6 flex justify-center">
                {{ $filteredHistory->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fadeIn .3s ease-in-out; }
    .grid-cols-10 { grid-template-columns: repeat(10, minmax(0, 1fr)); }
    .uppercase-text { text-transform: uppercase; }
    .pagination a, .pagination span {
        @apply px-3 py-2 mx-1 text-sm font-medium rounded border;
    }
    .pagination .page-link { @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-100; }
    .pagination .page-item.active .page-link { @apply bg-orange-500 text-white border-orange-500; }
    .pagination .page-item.disabled .page-link { @apply text-gray-400 cursor-not-allowed; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(a => {
                a.style.opacity = '0';
                a.style.transition = 'opacity .5s ease';
                setTimeout(() => a.remove(), 500);
            });
        }, 5000);
    });
</script>
@endsection