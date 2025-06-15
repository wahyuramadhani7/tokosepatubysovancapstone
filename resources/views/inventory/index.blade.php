@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">MANAGEMENT INVENTORY</h1>

        <!-- Inventory Information Cards -->
        <div class="rounded-lg p-4 md:p-6 mb-6" style="background-color: #292929;">
            <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4 text-white text-center">INVENTORY INFORMATION</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Total Produk</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalProducts ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Stok Menipis</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $lowStockProducts ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 md:p-6 rounded-lg shadow flex items-center transition-all hover:shadow-md">
                    <svg class="h-8 w-8 md:h-10 md:w-10 text-orange-500 mr-3 md:mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8h-4m-4 0H5" />
                    </svg>
                    <div>
                        <h3 class="text-sm md:text-base font-semibold uppercase">Total Stok</h3>
                        <p class="text-gray-600 text-base md:text-lg font-medium">{{ $totalStock ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar, QR Scanner Button, and Add Product Button -->
        <div style="background-color: #292929;" class="flex flex-col md:flex-row justify-between items-center p-3 mb-4 rounded-lg gap-3">
            <div class="flex flex-col md:flex-row gap-3 w-full max-w-xs">
                <form id="search-form" class="relative w-full">
                    <div class="relative rounded-lg overflow-hidden border border-orange-300">
                        <input type="text" name="search" id="search-input" placeholder="Search..." class="w-full p-2 pl-10 focus:outline-none">
                        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
                <button id="start-scanner" class="bg-white text-black px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm md:text-base flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Scan QR
                </button>
                <a href="{{ route('inventory.create') }}" class="bg-white text-black px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm md:text-base flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        <!-- QR Scanner Modal -->
        <div id="scanner-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Scan QR Code</h2>
                <div class="relative">
                    <video id="scanner-video" class="w-full h-64 bg-black rounded-lg"></video>
                    <canvas id="scanner-canvas" class="hidden"></canvas>
                </div>
                <div class="mt-4">
                    <label for="physical-stock" class="block text-sm font-medium text-gray-700">Stok Fisik</label>
                    <input type="number" id="physical-stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" min="0" value="0">
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button id="submit-stock" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">Submit</button>
                    <button id="close-scanner" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400">Tutup</button>
                </div>
                <div id="scan-result" class="mt-2 text-green-600"></div>
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

        <!-- Table - Desktop version -->
        <div class="shadow rounded-lg overflow-hidden hidden md:block p-4" style="background-color: #292929;">
            <div class="rounded-lg overflow-hidden">
                <!-- Table Headers -->
                <div class="grid grid-cols-8 gap-0">
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Produk</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Ukuran</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Warna</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Stok Sistem</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Stok Fisik</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Harga Jual</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">QR Code</div>
                    <div class="bg-orange-500 text-black font-medium py-2 px-3 text-center">Actions</div>
                </div>
                
                <!-- Table Body -->
                <div class="mt-1" id="desktop-table-body">
                    @forelse ($products ?? [] as $index => $product)
                        <div class="grid grid-cols-8 gap-0 items-center {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-200' }}">
                            <div class="p-3 text-black">{{ $product->name ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $product->size ?? '-' }}</div>
                            <div class="p-3 text-black text-center">{{ $product->color ?? '-' }}</div>
                            <div class="p-3 font-medium text-center {{ $product->stock < 5 ? 'text-red-600' : 'text-black' }}">{{ $product->stock ?? 0 }}</div>
                            <div class="p-3 font-medium text-center text-black" data-product-id="{{ $product->id }}">{{ $product->physical_stock ?? 0 }}</div>
                            <div class="p-3 text-black text-right">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                            <div class="p-3 text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                            </div>
                            <div class="p-3">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-600 hover:text-green-800 transition-colors" title="Cetak QR">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
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
                    @empty
                        <div class="bg-white p-6 text-center text-gray-500">
                            Tidak ada produk ditemukan.
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 flex justify-center" id="desktop-pagination">
                    @if(isset($products) && $products->hasPages())
                        {{ $products->links() }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4" id="mobile-cards">
            @forelse ($products ?? [] as $product)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="mb-2 flex items-center">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $product->name ?? '-' }}</h3>
                            <div class="text-sm text-gray-500">{{ $product->size ?? '-' }} | {{ $product->color ?? '-' }}</div>
                        </div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ urlencode(route('inventory.show', $product->id)) }}" alt="QR Code for {{ $product->name ?? '-' }}" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <div class="text-xs text-gray-500">Stok Sistem</div>
                            <div class="font-medium {{ $product->stock < 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->stock ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Stok Fisik</div>
                            <div class="font-medium text-gray-900" data-product-id="{{ $product->id }}">{{ $product->physical_stock ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Harga Jual</div>
                            <div class="font-medium text-gray-900">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('inventory.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('inventory.print_qr', $product->id) }}" class="text-green-500 hover:text-green-800 transition-colors flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print QR
                        </a>
                        <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-4">
                    Tidak ada produk ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center" id="mobile-pagination">
            @if(isset($products) && $products->hasPages())
                {{ $products->links() }}
            @endif
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
    .grid-cols-8 {
        grid-template-columns: repeat(8, minmax(0, 1fr));
    }
</style>

<script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch(searchInput.value.trim(), 1);
            });

            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performSearch(this.value.trim(), 1);
                }, 300);
            });
        }

        const scannerModal = document.getElementById('scanner-modal');
        const startScannerButton = document.getElementById('start-scanner');
        const closeScannerButton = document.getElementById('close-scanner');
        const submitStockButton = document.getElementById('submit-stock');
        const video = document.getElementById('scanner-video');
        const canvas = document.getElementById('scanner-canvas');
        const canvasContext = canvas.getContext('2d');
        const physicalStockInput = document.getElementById('physical-stock');
        const scanResultDiv = document.getElementById('scan-result');
        let stream = null;
        let productId = null;

        startScannerButton.addEventListener('click', async () => {
            scannerModal.classList.remove('hidden');
            scannerModal.classList.add('flex');
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(tick);
            } catch (err) {
                console.error('Error accessing camera:', err);
                showAlert('error', 'Gagal mengakses kamera: ' + err.message);
                scannerModal.classList.add('hidden');
                scannerModal.classList.remove('flex');
            }
        });

        closeScannerButton.addEventListener('click', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            scannerModal.classList.add('hidden');
            scannerModal.classList.remove('flex');
            productId = null;
            physicalStockInput.value = '0';
            scanResultDiv.innerHTML = '';
        });

        submitStockButton.addEventListener('click', () => {
            const physicalStock = physicalStockInput.value;
            if (!productId || !physicalStock || physicalStock < 0) {
                showAlert('error', 'Silakan pindai QR code dan masukkan stok fisik yang valid.');
                return;
            }

            fetch(`/inventory/${productId}/physical-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ physical_stock: parseInt(physicalStock) }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Stok fisik berhasil diperbarui.');
                    updateTableRow(productId, data.physical_stock); // Use the returned physical_stock
                    closeScannerButton.click();
                } else {
                    showAlert('error', data.error || 'Gagal memperbarui stok fisik.');
                }
            })
            .catch(error => {
                console.error('Error updating stock:', error);
                showAlert('error', 'Terjadi kesalahan saat memperbarui stok fisik.');
            });
        });

        function tick() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                if (code) {
                    const url = new URL(code.data);
                    const pathSegments = url.pathname.split('/');
                    productId = pathSegments[pathSegments.length - 1];
                    fetch(`/inventory/${productId}/json`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            showAlert('error', data.error);
                            scanResultDiv.innerHTML = '';
                            physicalStockInput.value = '0';
                        } else {
                            scanResultDiv.innerHTML = `<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-2">Produk ditemukan: ${data.name}</div>`;
                            const fetchedPhysicalStock = data.physical_stock !== null && data.physical_stock !== undefined ? parseInt(data.physical_stock) : 0;
                            physicalStockInput.value = String(fetchedPhysicalStock);
                            console.log('Fetched product data:', data);
                            console.log('Set physical stock to:', fetchedPhysicalStock);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product:', error);
                        showAlert('error', 'Gagal memverifikasi produk: ' + error.message);
                        scanResultDiv.innerHTML = '';
                        physicalStockInput.value = '0';
                    });
                }
            }
            if (!scannerModal.classList.contains('hidden')) {
                requestAnimationFrame(tick);
            }
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `border px-4 py-3 rounded relative mb-4 animate-fade-in ${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'}`;
            alertDiv.innerHTML = `
                <span class="block sm:inline">${message}</span>
                <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>`;
            document.querySelector('.max-w-7xl').insertAdjacentElement('afterbegin', alertDiv);
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                alertDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alertDiv.remove(), 500);
            }, 5000);
        }

        function updateTableRow(productId, physicalStock) {
            const rows = document.querySelectorAll('#desktop-table-body .grid-cols-8');
            rows.forEach(row => {
                const physicalStockCell = row.querySelector(`div[data-product-id="${productId}"]`);
                if (physicalStockCell) {
                    physicalStockCell.textContent = physicalStock;
                    physicalStockCell.className = 'p-3 font-medium text-center text-black';
                }
            });

            const cards = document.querySelectorAll('#mobile-cards > div');
            cards.forEach(card => {
                const physicalStockDiv = card.querySelector(`div[data-product-id="${productId}"]`);
                if (physicalStockDiv) {
                    physicalStockDiv.textContent = physicalStock;
                    physicalStockDiv.className = 'font-medium text-gray-900';
                }
            });
        }

        function performSearch(keyword, page) {
            if (!keyword) {
                location.reload();
                return;
            }

            const url = new URL('{{ route('inventory.search') }}');
            url.searchParams.set('search', keyword);
            url.searchParams.set('page', page);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                const desktopTableBody = document.getElementById('desktop-table-body');
                desktopTableBody.innerHTML = '';
                if (data.products.length === 0) {
                    desktopTableBody.innerHTML = '<div class="bg-white p-6 text-center text-gray-500">Tidak ada produk ditemukan.</div>';
                } else {
                    data.products.forEach((product, index) => {
                        const row = `
                            <div class="grid grid-cols-8 gap-0 items-center ${index % 2 === 0 ? 'bg-white' : 'bg-gray-200'}">
                                <div class="p-3 text-black">${product.name || '-'}</div>
                                <div class="p-3 text-black text-center">${product.size || '-'}</div>
                                <div class="p-3 text-black text-center">${product.color || '-'}</div>
                                <div class="p-3 font-medium text-center ${product.stock < 5 ? 'text-red-600' : 'text-black'}">${product.stock || 0}</div>
                                <div class="p-3 font-medium text-center text-black" data-product-id="${product.id}">${product.physical_stock || 0}</div>
                                <div class="p-3 text-black text-right">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div>
                                <div class="p-3 text-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route('inventory.show', ':id') }}'.replace(':id', product.id))}" alt="QR Code for ${product.name || '-'}" class="h-12 w-12 mx-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                                </div>
                                <div class="p-3">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-600 hover:text-green-800 transition-colors" title="Cetak QR">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
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
                            </div>`;
                        desktopTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }

                const mobileCards = document.getElementById('mobile-cards');
                mobileCards.innerHTML = '';
                if (data.products.length === 0) {
                    mobileCards.innerHTML = '<div class="text-center text-gray-500 p-4">Tidak ada produk ditemukan.</div>';
                } else {
                    data.products.forEach(product => {
                        const card = `
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="mb-2 flex items-center">
                                    <div>
                                        <h3 class="font-medium text-gray-900">${product.name || '-'}</h3>
                                        <div class="text-sm text-gray-500">${product.size || '-'} | ${product.color || '-'}</div>
                                    </div>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=${encodeURIComponent('{{ route('inventory.show', ':id') }}'.replace(':id', product.id))}" alt="QR Code for ${product.name || '-'}" class="h-12 w-12 ml-auto" onerror="this.src='{{ asset('images/qr-placeholder.png') }}';">
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div>
                                        <div class="text-xs text-gray-500">Stok Sistem</div>
                                        <div class="font-medium ${product.stock < 5 ? 'text-red-600' : 'text-gray-900'}">${product.stock || 0}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Stok Fisik</div>
                                        <div class="font-medium text-gray-900" data-product-id="${product.id}">${product.physical_stock || 0}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Harga Jual</div>
                                        <div class="font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price || 0)}</div>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-3 pt-2 border-t border-gray-100">
                                    <a href="{{ route('inventory.edit', ':id') }}".replace(':id', product.id) class="text-blue-500 hover:text-blue-700 transition-colors flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <a href="{{ route('inventory.print_qr', ':id') }}".replace(':id', product.id) class="text-green-500 hover:text-green-800 transition-colors flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print QR
                                    </a>
                                    <form action="{{ route('inventory.destroy', ':id') }}".replace(':id', product.id) method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors flex items-center">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>`;
                        mobileCards.insertAdjacentHTML('beforeend', card);
                    });
                }

                const desktopPagination = document.getElementById('desktop-pagination');
                desktopPagination.innerHTML = renderPagination(data.pagination, keyword);

                const mobilePagination = document.getElementById('mobile-pagination');
                mobilePagination.innerHTML = renderPagination(data.pagination, keyword);
            })
            .catch(error => {
                console.error('Error during search:', error);
                const desktopTableBody = document.getElementById('desktop-table-body');
                const mobileCards = document.getElementById('mobile-cards');
                desktopTableBody.innerHTML = '<div class="bg-white p-6 text-center text-gray-500">Terjadi kesalahan saat mencari produk.</div>';
                mobileCards.innerHTML = '<div class="text-center text-gray-500 p-4">Terjadi kesalahan saat mencari produk.</div>';
            });
        }

        function renderPagination(pagination, keyword) {
            if (pagination.last_page <= 1) return '';

            let html = '<nav class="pagination flex space-x-2">';
            for (let i = 1; i <= pagination.last_page; i++) {
                html += `<a href="#" class="pagination-link px-3 py-1 rounded ${i === pagination.current_page ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'}" data-page="${i}">${i}</a>`;
            }
            html += '</nav>';

            setTimeout(() => {
                document.querySelectorAll('.pagination-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        performSearch(keyword, page);
                    });
                });
            }, 0);

            return html;
        }

        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endsection