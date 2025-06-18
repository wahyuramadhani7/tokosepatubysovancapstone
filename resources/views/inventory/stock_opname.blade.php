@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">STOCK OPNAME</h1>

        <div class="bg-gray-900 rounded-lg p-4 md:p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <button id="start-scanner" class="bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center w-full md:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Mulai Scan QR
                    </button>
                </div>
                <a href="{{ route('inventory.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-black transition-colors flex items-center justify-center">
                    Kembali ke Inventory
                </a>
            </div>

            <div id="scanner-container" class="hidden mt-4">
                <div id="qr-scanner" class="w-full max-w-md mx-auto rounded-lg"></div>
                <button id="stop-scanner" type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors mt-2 w-full md:w-auto">
                    Stop Scan
                </button>
            </div>

            <div id="product-info" class="hidden mt-4 bg-gray-800 p-4 rounded-lg">
                <h2 class="text-lg font-semibold text-white mb-3">Informasi Produk</h2>
                <div id="product-details" class="text-white"></div>
                <div id="stock-status" class="text-white mt-2"></div>
                <form id="stock-opname-form" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="physical_stock" class="block text-sm font-medium text-white">Update Stok</label>
                        <input type="number" id="physical_stock" name="physical_stock" min="0" value="0" class="mt-1 p-2 w-full rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none" readonly required>
                    </div>
                    <button type="submit" class="bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">
                        Update Stok
                    </button>
                </form>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
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
    #qr-scanner video {
        width: 100%;
        height: auto;
        border-radius: 0.5rem;
    }
    input[readonly] {
        background-color: #4b5563 !important; /* bg-gray-600 */
        cursor: not-allowed;
    }
    .scanned-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        margin-bottom: 4px;
        background-color: #f87171;
        color: white;
        border-radius: 4px;
    }
    .scanned-item.new {
        background-color: #34d399;
    }
    .scanned-item button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startScannerBtn = document.getElementById('start-scanner');
    const stopScannerBtn = document.getElementById('stop-scanner');
    const scannerContainer = document.getElementById('scanner-container');
    const productInfo = document.getElementById('product-info');
    const productDetails = document.getElementById('product-details');
    const stockStatus = document.getElementById('stock-status');
    const stockOpnameForm = document.getElementById('stock-opname-form');
    const physicalStockInput = document.getElementById('physical_stock');
    let html5QrCode = null;
    let currentProductId = null;
    let scannedQRCodes = new Set(); // Menggunakan Set untuk menghindari duplikat
    let scannedCount = 0;
    let systemStock = 0;
    const scannedList = document.createElement('div'); // Container untuk daftar QR yang dipindai

    // Tambahkan container daftar QR ke DOM
    scannerContainer.parentNode.insertBefore(scannedList, scannerContainer.nextSibling);
    scannedList.className = 'mt-4';

    // Initialize QR scanner
    startScannerBtn.addEventListener('click', async () => {
        try {
            // Check camera permission
            const permission = await navigator.permissions.query({ name: 'camera' });
            if (permission.state === 'denied') {
                showAlert('error', 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.');
                return;
            }

            scannerContainer.classList.remove('hidden');
            productInfo.classList.add('hidden');
            startScannerBtn.classList.add('hidden');
            scannedCount = 0;
            scannedQRCodes.clear();
            systemStock = 0;
            physicalStockInput.value = scannedCount;
            stockStatus.innerHTML = '';
            scannedList.innerHTML = ''; // Bersihkan daftar QR sebelum memulai
            currentProductId = null;

            // Initialize scanner
            html5QrCode = new Html5Qrcode("qr-scanner");

            // Get available cameras
            const cameras = await Html5Qrcode.getCameras();
            if (cameras.length === 0) {
                showAlert('error', 'Tidak ada kamera yang ditemukan di perangkat Anda.');
                resetScanner();
                return;
            }

            // Prefer back camera (environment) if available
            const cameraId = cameras.find(cam => cam.label.toLowerCase().includes('back'))?.id || cameras[0].id;

            // Start scanner
            await html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    disableFlip: false
                },
                onScanSuccess,
                onScanError
            );
        } catch (err) {
            console.error('Error starting QR scanner:', err);
            showAlert('error', 'Gagal memulai scanner: ' + err.message);
            resetScanner();
        }
    });

    // Stop QR scanner
    stopScannerBtn.addEventListener('click', () => {
        resetScanner();
    });

    // Handle successful QR scan
    function onScanSuccess(decodedText) {
        if (!decodedText.includes('inventory')) {
            showAlert('error', 'QR code tidak valid untuk inventory.');
            return;
        }

        // Extract productId and unitCode from URL
        const urlParts = decodedText.split('/');
        const productIndex = urlParts.indexOf('inventory');
        if (productIndex === -1 || productIndex + 1 >= urlParts.length) {
            showAlert('error', 'Format QR code tidak valid.');
            return;
        }

        const productId = urlParts[productIndex + 1];
        const isUnitQR = urlParts[productIndex + 2] === 'unit';
        const unitCode = isUnitQR ? urlParts[productIndex + 3]?.split('?')[0] : null;

        // Validate productId as a number
        if (!/^\d+$/.test(productId)) {
            showAlert('error', 'ID produk tidak valid.');
            return;
        }

        if (!currentProductId) {
            // First scan, set product and fetch data
            currentProductId = productId;
            scannedCount = 1;
            scannedQRCodes.add(decodedText);
            physicalStockInput.value = scannedCount;
            fetchProductData(productId);
            productInfo.classList.remove('hidden');
            addScannedItem(decodedText, true); // Tambah item dengan status baru
            showAlert('success', 'QR code dipindai. Update stok: ' + scannedCount);
        } else if (productId === currentProductId) {
            // Same product, different QR code
            if (!scannedQRCodes.has(decodedText)) {
                scannedCount++;
                scannedQRCodes.add(decodedText);
                physicalStockInput.value = scannedCount;
                updateStockStatus();
                addScannedItem(decodedText, true); // Tambah item dengan status baru
                showAlert('success', 'QR code dipindai. Update stok: ' + scannedCount);
            } else {
                // QR code sudah dipindai, tambahkan ke daftar tanpa notifikasi berulang
                if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
                    addScannedItem(decodedText, false); // Tambah item tanpa status baru
                }
            }
        } else {
            // Different product
            showAlert('error', 'QR code dari produk lain. Silakan pindai QR code untuk produk yang sama.');
        }
    }

    function onScanError(errorMessage) {
        console.warn('QR scan error:', errorMessage);
    }

    // Fetch product data by productId
    function fetchProductData(productId) {
        fetch(`/inventory/${productId}/json`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showAlert('error', data.error);
                resetScanner();
                return;
            }

            systemStock = data.stock || 0;
            productDetails.innerHTML = `
                <p><strong>Nama:</strong> ${data.name || '-'}</p>
                <p><strong>Ukuran:</strong> ${data.size || '-'}</p>
                <p><strong>Warna:</strong> ${data.color || '-'}</p>
                <p><strong>Stok Sistem:</strong> ${systemStock}</p>
            `;
            stockOpnameForm.action = `/inventory/${data.id}/physical-stock`;
            updateStockStatus();
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            showAlert('error', 'Gagal mengambil data produk');
            resetScanner();
        });
    }

    // Update stock status message
    function updateStockStatus() {
        const difference = scannedCount - systemStock;
        let statusMessage = '';
        let statusClass = '';

        if (difference === 0) {
            statusMessage = 'Stok sesuai dengan sistem.';
            statusClass = 'text-green-400';
        } else if (difference > 0) {
            statusMessage = `Produk tidak sesuai, lebih ${difference} unit.`;
            statusClass = 'text-yellow-400';
        } else {
            statusMessage = `Produk tidak sesuai, kurang ${Math.abs(difference)} unit.`;
            statusClass = 'text-red-400';
        }

        stockStatus.innerHTML = `<p class="${statusClass}"><strong>Status Stok:</strong> ${statusMessage}</p>`;
    }

    // Handle stock opname form submission
    stockOpnameForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = '{{ route('inventory.index') }}';
                }, 2000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating stock:', error);
            showAlert('error', 'Gagal memperbarui stok: ' + error.message);
        });
    });

    // Reset scanner state
    function resetScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                scannerContainer.classList.add('hidden');
                productInfo.classList.add('hidden');
                startScannerBtn.classList.remove('hidden');
                scannedCount = 0;
                scannedQRCodes.clear();
                systemStock = 0;
                physicalStockInput.value = scannedCount;
                stockStatus.innerHTML = '';
                scannedList.innerHTML = ''; // Bersihkan daftar QR
                currentProductId = null;
            }).catch(err => {
                console.error('Error stopping scanner:', err);
            });
        } else {
            scannerContainer.classList.add('hidden');
            productInfo.classList.add('hidden');
            startScannerBtn.classList.remove('hidden');
            scannedCount = 0;
            scannedQRCodes.clear();
            systemStock = 0;
            physicalStockInput.value = scannedCount;
            stockStatus.innerHTML = '';
            scannedList.innerHTML = ''; // Bersihkan daftar QR
            currentProductId = null;
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `bg-${type === 'success' ? 'green' : 'red'}-100 border border-${type === 'success' ? 'green' : 'red'}-400 text-${type === 'success' ? 'green' : 'red'}-700 px-4 py-3 rounded relative mb-4 animate-fade-in`;
        alertDiv.innerHTML = `
            <span class="block sm:inline">${message}</span>
            <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        document.querySelector('.max-w-7xl').prepend(alertDiv);

        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
    }

    // Tambahkan item ke daftar QR yang dipindai
    function addScannedItem(decodedText, isNew) {
        if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
            const item = document.createElement('div');
            item.className = `scanned-item ${isNew ? 'new' : ''}`;
            item.setAttribute('data-qr', decodedText);
            item.innerHTML = `
                ${isNew ? 'QR code dipindai. ' : 'QR code ini sudah dipindai. '}
                <button onclick="this.parentElement.remove()">×</button>
            `;
            scannedList.appendChild(item);

            // Hapus item dari Set jika dihapus manual
            item.querySelector('button').addEventListener('click', () => {
                scannedQRCodes.delete(decodedText);
                scannedCount = scannedQRCodes.size;
                physicalStockInput.value = scannedCount;
                updateStockStatus();
            });

            // Perbarui hitungan stok setelah menambahkan item baru
            if (isNew) {
                scannedCount = scannedQRCodes.size;
                physicalStockInput.value = scannedCount;
                updateStockStatus();
            }
        }
    }
});
</script>
@endsection