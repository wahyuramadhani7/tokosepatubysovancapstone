@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">RIWAYAT STOCK OPNAME</h1>

        <div class="p-4 md:p-6 rounded-lg shadow" style="background-color: #292929;">
            <div class="overflow-x-auto">
                <table class="w-full text-sm md:text-base">
                    <thead class="bg-orange-500 text-black">
                        <tr>
                            <th class="py-2 px-3 text-left">Tanggal</th>
                            <th class="py-2 px-3 text-left">Produk</th>
                            <th class="py-2 px-3 text-center">Stok Buku</th>
                            <th class="py-2 px-3 text-center">Stok Fisik</th>
                            <th class="py-2 px-3 text-center">Selisih</th>
                            <th class="py-2 px-3 text-left">Catatan</th>
                            <th class="py-2 px-3 text-left">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOpnames as $index => $opname)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                                <td class="py-2 px-3">{{ $opname->opname_date->format('d-m-Y H:i') }}</td>
                                <td class="py-2 px-3">{{ $opname->product->name ?? '-' }}</td>
                                <td class="py-2 px-3 text-center">{{ $opname->book_stock }}</td>
                                <td class="py-2 px-3 text-center">{{ $opname->physical_stock }}</td>
                                <td class="py-2 px-3 text-center {{ $opname->difference != 0 ? 'text-red-600' : 'text-green-600' }}">{{ $opname->difference }}</td>
                                <td class="py-2 px-3">{{ $opname->notes ?? '-' }}</td>
                                <td class="py-2 px-3">{{ $opname->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada riwayat stock opname.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                {{ $stockOpnames->links() }}
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
</style>
@endsection