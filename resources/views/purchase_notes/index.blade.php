@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">CATATAN BARANG MASUK</h1>

        <!-- Monthly Filter Form -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow">
            <form action="{{ route('purchase_notes.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700">Bulan (Tahun: {{ date('Y') }})</label>
                    <select name="month" id="month" class="mt-1 block w-full bg-white text-black text-sm rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('month', request('month')) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    @error('month')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="bg-orange-500 text-black font-medium text-sm rounded-lg px-4 py-2 hover:bg-orange-600 transition-colors">Filter</button>
                    <a href="{{ route('purchase_notes.index') }}" class="bg-gray-500 text-white font-medium text-sm rounded-lg px-4 py-2 hover:bg-gray-600 transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Purchase Notes Information -->
        <div class="rounded-lg p-4 md:p-6 mb-6" style="background-color: #292929;">
            <h2 class="text-lg md:text-xl font-semibold mb-4 md:mb-6 text-white text-center">INFORMASI CATATAN BARANG MASUK</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 md:gap-6">
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01M12 12h.01" />
                    </svg>
                    <div class="w-full">
                        <h3 class="text-sm md:text-base font-semibold uppercase">Jumlah Catatan per Jenis</h3>
                        <select id="type-counts" class="w-full bg-white text-gray-600 text-sm md:text-base rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-orange-500 capitalize-text">
                            @forelse ($typeCounts ?? [] as $type => $count)
                                <option value="{{ $type }}">{{ $type }} ({{ $count }} catatan)</option>
                            @empty
                                <option value="">Tidak ada catatan</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m-5 10V7m5 10h2a2 2 0 002-2V7a2 2 0 00-2-2h-2M7 7H5a2 2 0 00-2 2v6a2 2 0 002 2h2" />
                    </svg>
                    <div class="w-full">
                        <h3 class="text-sm md:text-base font-semibold uppercase">Jumlah Catatan per Produk</h3>
                        <select id="product-counts" class="w-full bg-white text-gray-600 text-sm md:text-base rounded-md py-1 px-2 focus:outline-none focus:ring-2 focus:ring-orange-500 uppercase-text">
                            @forelse ($productCounts ?? [] as $product => $count)
                                <option value="{{ $product }}">{{ $product }} ({{ $count }} catatan)</option>
                            @empty
                                <option value="">Tidak ada produk</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
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

        <!-- Buttons for Create and Export -->
        <div class="mb-6 flex flex-wrap gap-4">
            <a href="{{ route('purchase_notes.create') }}" class="bg-white text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-gray-100 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Catatan Pembelian
            </a>
            <a href="{{ route('purchase_notes.export.pdf') . (request()->query() ? '?' . request()->getQueryString() : '') }}" class="bg-orange-500 text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-orange-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H3a2 2 0 01-2-2V3a2 2 0 012-2h18a2 2 0 012 2v16a2 2 0 01-2 2z" />
                </svg>
                Export ke PDF
            </a>
            <a href="{{ route('purchase_notes.export.excel') . (request()->query() ? '?' . request()->getQueryString() : '') }}" class="bg-green-500 text-white font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-green-600 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H3a2 2 0 01-2-2V3a2 2 0 012-2h18a2 2 0 012 2v16a2 2 0 01-2 2z" />
                </svg>
                Export ke Excel
            </a>
        </div>

        <!-- Table - Desktop version -->
        <div class="shadow rounded-lg overflow-hidden hidden md:block p-4" style="background-color: #292929;">
            <div class="rounded-lg overflow-hidden">
                <!-- Table Headers -->
                <div class="grid grid-cols-10 gap-0">
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">No</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Jenis</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Nama Produk</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Ukuran</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Warna</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Harga Asli</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Harga Diskon</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Jumlah</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Dibuat Oleh</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Aksi</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1">
                    @php
                        $currentGroup = '';
                        $rowNumber = 0;
                    @endphp
                    @forelse ($purchaseNotes->groupBy('type') as $type => $notes)
                        <div class="bg-orange-500 text-black font-semibold py-2 px-3 capitalize-text">{{ $type }}</div>
                        @foreach ($notes as $note)
                            @php
                                $rowNumber++;
                            @endphp
                            <div class="grid grid-cols-10 gap-0 items-center {{ $loop->iteration % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                                <div class="p-3 text-black text-center">{{ $rowNumber }}</div>
                                <div class="p-3 text-black text-center capitalize-text">{{ $note->type }}</div>
                                <div class="p-3 text-black uppercase-text">{{ $note->product_name }}</div>
                                <div class="p-3 text-black text-center">{{ $note->size ?? '-' }}</div>
                                <div class="p-3 text-black text-center uppercase-text">{{ $note->color ?? '-' }}</div>
                                <div class="p-3 text-black text-right">Rp {{ number_format($note->original_price, 0, ',', '.') }}</div>
                                <div class="p-3 text-black text-right">{{ $note->discount_price ? 'Rp ' . number_format($note->discount_price, 0, ',', '.') : '-' }}</div>
                                <div class="p-3 text-black text-center">{{ $note->quantity }}</div>
                                <div class="p-3 text-black text-center capitalize-text">{{ $note->user->name ?? '-' }}</div>
                                <div class="p-3">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('purchase_notes.edit', $note->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('purchase_notes.destroy', $note->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="border-t border-gray-300 my-2"></div>
                    @empty
                        <div class="bg-white p-6 text-center text-gray-500">
                            Tidak ada catatan pembelian ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @php
                $currentGroup = '';
                $rowNumber = 0;
            @endphp
            @forelse ($purchaseNotes->groupBy('type') as $type => $notes)
                <div class="bg-orange-500 text-black font-semibold py-2 px-3 rounded-t-lg capitalize-text">{{ $type }}</div>
                <div class="space-y-4">
                    @foreach ($notes as $note)
                        @php
                            $rowNumber++;
                        @endphp
                        <div class="bg-white rounded-lg shadow p-4">
                            <div class="mb-2">
                                <h3 class="font-medium text-gray-900">
                                    No: {{ $rowNumber }} - <span class="uppercase-text">{{ $note->product_name }}</span>
                                </h3>
                                <div class="text-sm text-gray-500 capitalize-text">{{ $note->type }} | {{ $note->size ?? '-' }} | <span class="uppercase-text">{{ $note->color ?? '-' }}</span></div>
                                <div class="text-sm text-gray-500">Dibuat Oleh: <span class="capitalize-text">{{ $note->user->name ?? '-' }}</span></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div>
                                    <div class="text-xs text-gray-500">Harga Asli</div>
                                    <div class="font-medium text-gray-900">Rp {{ number_format($note->original_price, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Harga Diskon</div>
                                    <div class="font-medium text-gray-900">{{ $note->discount_price ? 'Rp ' . number_format($note->discount_price, 0, ',', '.') : '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Jumlah</div>
                                    <div class="font-medium text-gray-900">{{ $note->quantity }}</div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                                <a href="{{ route('purchase_notes.edit', $note->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('purchase_notes.destroy', $note->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center text-gray-500 p-4">
                    Tidak ada catatan pembelian ditemukan.
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
    .capitalize-text {
        text-transform: capitalize;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection