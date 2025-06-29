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
                <input type="text" id="barcode-input" style="opacity: 0; position: absolute; width: 0; height: 0;" />
                <button id="stop-scanner" type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors mt-2 w-full md:w-auto">
                    Stop Scan
                </button>
            </div>

            <div id="products-list" class="mt-4">
                <h2 class="text-lg font-semibold text-white mb-3">Daftar Produk yang Dipindai</h2>
                <div id="products-table" class="bg-gray-800 p-4 rounded-lg text-white"></div>
                <button id="save-report" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors mt-4 hidden">
                    Simpan Laporan
                </button>
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
    #products-table table {
        width: 100%;
        border-collapse: collapse;
    }
    #products-table th, #products-table td {
        padding: 8px;
        border: 1px solid #4b5563;
        text-align: left;
    }
    #products-table th {
        background-color: #374151;
    }
    #products-table input {
        background-color: #4b5563;
        color: white;
        padding: 4px;
        border: none;
        border-radius: 4px;
        width: 80px;
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startScannerBtn = document.getElementById('start-scanner');
    const stopScannerBtn = document.getElementById('stop-scanner');
    const scannerContainer = document.getElementById('scanner-container');
    const barcodeInput = document.getElementById('barcode-input');
    const productsTable = document.getElementById('products-table');
    const saveReportBtn = document.getElementById('save-report');
    const scannedList = document.createElement('div');
    scannerContainer.parentNode.insertBefore(scannedList, scannerContainer.nextSibling);
    scannedList.className = 'mt-4';

    let html5QrCode = null;
    let scannedProducts = {}; // Menyimpan data produk yang discan
    let scannedQRCodes = new Set();

    // Initialize QR scanner
    startScannerBtn.addEventListener('click', async () => {
        try {
            // Check camera permission
            const permission = await navigator.permissions.query({ name: 'camera' });
            if (permission.state === 'denied') {
                showAlert('error', 'Izin kamera ditolak. Silakan izinkan akses kamera atau gunakan scanner fisik.');
            }

            scannerContainer.classList.remove('hidden');
            startScannerBtn.classList.add('hidden');
            scannedProducts = {};
            scannedQRCodes.clear();
            updateProductsTable();
            barcodeInput.focus();

            // Start camera-based QR scanner if available
            if (permission.state !== 'denied') {
                html5QrCode = new Html5Qrcode("qr-scanner");
                const cameras = await Html5Qrcode.getCameras();
                if (cameras.length === 0) {
                    showAlert('warning', 'Tidak ada kamera yang ditemukan. Gunakan scanner fisik.');
                } else {
                    const cameraId = cameras.find(cam => cam.label.toLowerCase().includes('back'))?.id || cameras[0].id;
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
                }
            }
        } catch (err) {
            console.error('Error starting QR scanner:', err);
            showAlert('error', 'Gagal memulai scanner: ' + err.message);
            resetScanner();
        }
    });

    // Handle input from physical scanner
    barcodeInput.addEventListener('input', function(e) {
        const decodedText = e.target.value.trim();
        if (decodedText) {
            onScanSuccess(decodedText);
            barcodeInput.value = '';
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

        const urlParts = decodedText.split('/');
        const productIndex = urlParts.indexOf('inventory');
        if (productIndex === -1 || productIndex + 1 >= urlParts.length) {
            showAlert('error', 'Format QR code tidak valid.');
            return;
        }

        const productId = urlParts[productIndex + 1];
        if (!/^\d+$/.test(productId)) {
            showAlert('error', 'ID produk tidak valid.');
            return;
        }

        const isNewScan = !scannedQRCodes.has(decodedText);
        if (isNewScan) {
            scannedQRCodes.add(decodedText);
            addScannedItem(decodedText, true);

            if (!scannedProducts[productId]) {
                scannedProducts[productId] = {
                    count: 0,
                    systemStock: 0,
                    name: '-',
                    size: '-',
                    color: '-',
                    qrCodes: new Set()
                };
                fetchProductData(productId);
            }
            scannedProducts[productId].count++;
            scannedProducts[productId].qrCodes.add(decodedText);
            updateProductsTable();
            showAlert('success', `QR code dipindai untuk produk ID ${productId}.`);
        } else if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
            addScannedItem(decodedText, false);
            showAlert('warning', 'QR code ini sudah dipindai.');
        }
    }

    function onScanError(errorMessage) {
        console.warn('QR scan error:', errorMessage);
    }

    // Fetch product data
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
                return;
            }
            scannedProducts[productId] = {
                ...scannedProducts[productId],
                name: data.name || '-',
                size: data.size || '-',
                color: data.color || '-',
                systemStock: data.stock || 0
            };
            updateProductsTable();
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            showAlert('error', 'Gagal mengambil data produk');
        });
    }

    // Update products table
    function updateProductsTable() {
        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Warna</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
        `;
        for (const [productId, product] of Object.entries(scannedProducts)) {
            const difference = product.count - product.systemStock;
            let statusMessage = '';
            let statusClass = '';
            if (difference === 0) {
                statusMessage = 'Sesuai';
                statusClass = 'text-green-400';
            } else if (difference > 0) {
                statusMessage = `Lebih ${difference}`;
                statusClass = 'text-yellow-400';
            } else {
                statusMessage = `Kurang ${Math.abs(difference)}`;
                statusClass = 'text-red-400';
            }
            html += `
                <tr>
                    <td>${product.name}</td>
                    <td>${product.size}</td>
                    <td>${product.color}</td>
                    <td>${product.systemStock}</td>
                    <td>
                        <input type="number" min="0" value="${product.count}" data-product-id="${productId}" class="physical-stock-input">
                    </td>
                    <td class="${statusClass}">${statusMessage}</td>
                    <td>
                        <button class="update-stock-btn bg-orange-500 text-black px-2 py-1 rounded hover:bg-orange-600" data-product-id="${productId}">Update</button>
                    </td>
                </tr>
            `;
        }
        html += `
                </tbody>
            </table>
        `;
        productsTable.innerHTML = html;

        // Show/hide save report button
        saveReportBtn.classList.toggle('hidden', Object.keys(scannedProducts).length === 0);

        // Add event listeners for stock inputs and update buttons
        document.querySelectorAll('.physical-stock-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                scannedProducts[productId].count = parseInt(this.value) || 0;
                updateProductsTable();
            });
        });

        document.querySelectorAll('.update-stock-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                updateStock(productId);
            });
        });
    }

    // Update stock for a product
    function updateStock(productId) {
        const formData = new FormData();
        formData.append('physical_stock', scannedProducts[productId].count);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/inventory/${productId}/physical-stock`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                delete scannedProducts[productId];
                scannedQRCodes = new Set([...scannedQRCodes].filter(qr => !qr.includes(`/inventory/${productId}`)));
                updateProductsTable();
                scannedList.innerHTML = '';
                [...scannedQRCodes].forEach(qr => addScannedItem(qr, false));
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating stock:', error);
            showAlert('error', 'Gagal mencatat stok fisik: ' + error.message);
        });
    }

    // Save report (optional)
    saveReportBtn.addEventListener('click', () => {
        const report = Object.entries(scannedProducts).map(([productId, product]) => ({
            product_id: productId,
            name: product.name,
            size: product.size,
            color: product.color,
            system_stock: product.systemStock,
            physical_stock: product.count,
            difference: product.count - product.systemStock
        }));
        console.log('Stock Opname Report:', report);
        showAlert('success', 'Laporan stok opname disimpan di konsol.'); // Bisa diganti dengan fetch ke endpoint baru
    });

    // Reset scanner state
    function resetScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                scannerContainer.classList.add('hidden');
                startScannerBtn.classList.remove('hidden');
                scannedProducts = {};
                scannedQRCodes.clear();
                productsTable.innerHTML = '';
                scannedList.innerHTML = '';
                saveReportBtn.classList.add('hidden');
                barcodeInput.blur();
            }).catch(err => {
                console.error('Error stopping scanner:', err);
            });
        } else {
            scannerContainer.classList.add('hidden');
            startScannerBtn.classList.remove('hidden');
            scannedProducts = {};
            scannedQRCodes.clear();
            productsTable.innerHTML = '';
            scannedList.innerHTML = '';
            saveReportBtn.classList.add('hidden');
            barcodeInput.blur();
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `bg-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-100 border border-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-400 text-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-700 px-4 py-3 rounded relative mb-4 animate-fade-in`;
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

    // Add scanned item to list
    function addScannedItem(decodedText, isNew) {
        if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
            const item = document.createElement('div');
            item.className = `scanned-item ${isNew ? 'new' : ''}`;
            item.setAttribute('data-qr', decodedText);
            item.innerHTML = `
                ${isNew ? 'QR code dipindai.' : 'QR code ini sudah dipindai.'}
                <button onclick="this.parentElement.remove()">×</button>
            `;
            scannedList.appendChild(item);

            item.querySelector('button').addEventListener('click', () => {
                scannedQRCodes.delete(decodedText);
                const productId = decodedText.split('/').find((part, index, arr) => arr[index - 1] === 'inventory');
                if (productId && scannedProducts[productId]) {
                    scannedProducts[productId].qrCodes.delete(decodedText);
                    scannedProducts[productId].count = scannedProducts[productId].qrCodes.size;
                    if (scannedProducts[productId].count === 0) {
                        delete scannedProducts[productId];
                    }
                    updateProductsTable();
                }
            });
        }
    }
});
</script>
@endsection