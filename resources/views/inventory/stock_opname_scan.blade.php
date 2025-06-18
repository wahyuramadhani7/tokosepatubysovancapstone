@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">STOCK OPNAME VIA QR</h1>

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

        <div class="p-4 md:p-6 rounded-lg shadow" style="background-color: #292929;">
            <form id="qr-scan-form" class="space-y-4">
                <div>
                    <label for="qr-input" class="block text-white mb-2">Scan QR Code (masukkan ID produk):</label>
                    <input type="text" id="qr-input" class="w-full p-2 rounded border border-orange-300" placeholder="Masukkan ID produk dari QR code">
                </div>
                <div id="product-info" class="hidden bg-gray-100 p-4 rounded">
                    <h3 id="product-name" class="font-medium text-gray-900"></h3>
                    <p id="product-details" class="text-sm text-gray-500"></p>
                    <div class="mt-2">
                        <label for="physical-stock" class="block text-sm text-gray-700">Stok Fisik:</label>
                        <input type="number" id="physical-stock" name="physical_stock" class="w-20 p-1 border rounded" min="0" required>
                    </div>
                    <div class="mt-2">
                        <label for="notes" class="block text-sm text-gray-700">Catatan (opsional):</label>
                        <input type="text" id="notes" name="notes" class="w-full p-1 border rounded" placeholder="Catatan">
                    </div>
                </div>
                <button type="submit" id="submit-btn" class="bg-orange-500 text-black px-4 py-2 rounded hover:bg-orange-600 transition-colors hidden">Simpan</button>
            </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('qr-scan-form');
        const qrInput = document.getElementById('qr-input');
        const productInfo = document.getElementById('product-info');
        const productName = document.getElementById('product-name');
        const productDetails = document.getElementById('product-details');
        const submitBtn = document.getElementById('submit-btn');

        qrInput.addEventListener('input', function() {
            const productId = this.value.trim();
            if (productId) {
                fetch('{{ route('inventory.json', ':id') }}'.replace(':id', productId), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        productInfo.classList.add('hidden');
                        submitBtn.classList.add('hidden');
                        alert('Produk tidak ditemukan.');
                    } else {
                        productName.textContent = data.name;
                        productDetails.textContent = `Ukuran: ${data.size} | Warna: ${data.color} | Stok Buku: ${data.stock}`;
                        productInfo.classList.remove('hidden');
                        submitBtn.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching product:', error);
                    alert('Terjadi kesalahan saat memuat data produk.');
                });
            } else {
                productInfo.classList.add('hidden');
                submitBtn.classList.add('hidden');
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = qrInput.value.trim();
            const physicalStock = document.getElementById('physical-stock').value;
            const notes = document.getElementById('notes').value;

            fetch('{{ route('inventory.updatePhysicalStock', ':id') }}'.replace(':id', productId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    physical_stock: physicalStock,
                    notes: notes,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    form.reset();
                    productInfo.classList.add('hidden');
                    submitBtn.classList.add('hidden');
                } else {
                    alert(data.error || 'Terjadi kesalahan saat menyimpan.');
                }
            })
            .catch(error => {
                console.error('Error submitting stock opname:', error);
                alert('Terjadi kesalahan saat menyimpan.');
            });
        });

        // Auto-dismiss alerts after 5 seconds
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