@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">PRODUCT STATUS</h1>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('inventory.index') }}" class="bg-white text-black font-medium text-sm md:text-base rounded-lg px-4 py-2.5 hover:bg-gray-100 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Inventory
            </a>
        </div>

        <!-- Product Status Section -->
        <div class="rounded-lg p-4 md:p-6 mb-6" style="background-color: #292929;">
            <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-white text-center">PRODUCT STATUS</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Newly Added Products -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-sm md:text-base font-semibold uppercase mb-2">Produk Baru Ditambahkan</h3>
                    @if (!empty($newProducts))
                        <ul class="space-y-2">
                            @foreach ($newProducts as $productId)
                                @php
                                    $product = \App\Models\Product::find($productId);
                                    $brandNames = session('brand_names', []);
                                    $brand = $brandNames[$productId] ?? ($product ? explode(' ', trim($product->name))[0] : 'Unknown');
                                    $model = $product ? trim(str_replace($brand, '', $product->name)) : 'Unknown';
                                @endphp
                                @if ($product)
                                    <li class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $brand }} {{ $model }}</span> (Ukuran: {{ $product->size ?? '-' }}, Warna: {{ $product->color ?? '-' }}, Stok: {{ $product->productUnits()->where('is_active', true)->count() }})
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Tidak ada produk baru ditambahkan.</p>
                    @endif
                </div>
                <!-- Sold Products -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-sm md:text-base font-semibold uppercase mb-2">Produk Terjual</h3>
                    @php
                        $soldProducts = \App\Models\ProductUnit::where('is_active', false)->with('product')->get()->groupBy('product_id')->map(function ($units) {
                            return $units->first()->product;
                        })->filter();
                    @endphp
                    @if ($soldProducts->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($soldProducts as $product)
                                @php
                                    $brandNames = session('brand_names', []);
                                    $brand = $brandNames[$product->id] ?? explode(' ', trim($product->name))[0];
                                    $model = trim(str_replace($brand, '', $product->name));
                                    $soldCount = \App\Models\ProductUnit::where('product_id', $product->id)->where('is_active', false)->count();
                                @endphp
                                <li class="text-sm text-gray-600">
                                    <span class="font-medium">{{ $brand }} {{ $model }}</span> (Ukuran: {{ $product->size ?? '-' }}, Warna: {{ $product->color ?? '-' }}, Terjual: {{ $soldCount }})
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Tidak ada produk yang terjual.</p>
                    @endif
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
    .uppercase-text {
        text-transform: uppercase;
    }
</style>
@endsection