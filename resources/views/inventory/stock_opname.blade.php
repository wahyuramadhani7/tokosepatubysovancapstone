@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-white py-3 px-6 rounded-lg shadow inline-block">STOCK OPNAME</h1>

        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 mb-6">
            <!-- Search and Scanner Controls -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="product-search" class="w-full border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari produk...">
                        <div id="search-results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-64 overflow-y-auto hidden"></div>
                    </div>
                </div>
                <button id="start-scanner" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center w-full md:w-auto justify-center font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Mulai Scan QR
                </button>
                <a href="{{ route('inventory.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center font-semibold">
                    Kembali ke Inventory
                </a>
            </div>

            <!-- Scanner Section -->
            <div id="scanner-container" class="hidden mt-6">
                <div id="qr-scanner" class="w-full max-w-md mx-auto rounded-lg overflow-hidden shadow-md"></div>
                <input type="text" id="barcode-input" class="barcode-input" />
                <button id="stop-scanner" type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors mt-4 w-full md:w-auto font-semibold">
                    Stop Scan
                </button>
            </div>

            <!-- Scanned Products Section -->
            <div id="products-list" class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Daftar Produk yang Dipindai</h2>
                <div id="products-table" class="bg-gray-50 p-4 rounded-lg shadow text-gray-800"></div>
                <button id="save-report" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors mt-4 hidden w-full md:w-auto font-semibold shadow-md border-2 border-green-800">
                    Simpan Laporan
                </button>
            </div>

            <!-- Stock Opname Reports Section -->
            <div id="reports-list" class="mt-8">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold text-gray-800">Laporan Stock Opname</h2>
                    @if(!empty($reports))
                        <form action="{{ route('inventory.delete_all_reports') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua laporan?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-semibold shadow-md">Hapus Semua Laporan</button>
                        </form>
                    @endif
                </div>
                <div id="reports-table" class="bg-gray-50 p-4 rounded-lg shadow text-gray-800 overflow-x-auto">
                    @if(!empty($reports))
                        @php
                            $groupedReports = [];
                            $brandQuantities = [];
                            foreach ($reports as $index => $report) {
                                $brand = $report['brand'] ?? explode(' ', $report['name'])[0];
                                $groupedReports[$brand][] = ['index' => $index, 'report' => $report];
                                $brandQuantities[$brand] = ($brandQuantities[$brand] ?? 0) + $report['physical_stock'];
                            }
                            ksort($groupedReports);
                            foreach ($groupedReports as $brand => $reports) {
                                usort($groupedReports[$brand], function($a, $b) {
                                    return strcmp($a['report']['name'], $b['report']['name']);
                                });
                            }
                            $totalSystemStock = 0;
                            $totalPhysicalStock = 0;
                            $totalDifference = 0;
                        @endphp
                        @foreach($groupedReports as $brand => $brandReports)
                            <h3 class="text-md font-semibold text-gray-800 mt-4 mb-2">{{ $brand }} (Total Quantity: {{ $brandQuantities[$brand] }})</h3>
                            <table class="w-full border-collapse text-sm mb-6">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="p-3 border border-gray-300">Tanggal</th>
                                        <th class="p-3 border border-gray-300">Produk</th>
                                        <th class="p-3 border border-gray-300">Ukuran</th>
                                        <th class="p-3 border border-gray-300">Warna</th>
                                        <th class="p-3 border border-gray-300">Stok Sistem</th>
                                        <th class="p-3 border border-gray-300">Stok Fisik</th>
                                        <th class="p-3 border border-gray-300">Selisih</th>
                                        <th class="p-3 border border-gray-300">QR Code</th>
                                        <th class="p-3 border border-gray-300">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brandReports as $item)
                                        @php
                                            $report = $item['report'];
                                            $index = $item['index'];
                                            $totalSystemStock += $report['system_stock'];
                                            $totalPhysicalStock += $report['physical_stock'];
                                            $totalDifference += $report['difference'];
                                        @endphp
                                        <tr class="hover:bg-gray-100">
                                            <td class="p-3 border border-gray-300">{{ \Carbon\Carbon::parse($report['timestamp'])->format('d/m/Y H:i') }}</td>
                                            <td class="p-3 border border-gray-300">{{ $report['name'] }}</td>
                                            <td class="p-3 border border-gray-300">{{ $report['size'] }}</td>
                                            <td class="p-3 border border-gray-300">{{ $report['color'] }}</td>
                                            <td class="p-3 border border-gray-300">{{ $report['system_stock'] }}</td>
                                            <td class="p-3 border border-gray-300">{{ $report['physical_stock'] }}</td>
                                            <td class="p-3 border border-gray-300 {{ $report['difference'] < 0 ? 'text-red-500' : ($report['difference'] > 0 ? 'text-yellow-500' : 'text-green-500') }}">
                                                {{ $report['difference'] > 0 ? '+' : '' }}{{ $report['difference'] }}
                                            </td>
                                            <td class="p-3 border border-gray-300">
                                                <button class="toggle-qr-codes text-blue-500 hover:text-blue-700 font-semibold" data-index="{{ $index }}">
                                                    Lihat QR Codes
                                                </button>
                                                <div class="qr-codes-details hidden mt-2" data-index="{{ $index }}">
                                                    <p class="text-sm font-semibold">QR Code Discan ({{ count($report['scanned_qr_codes'] ?? []) }}):</p>
                                                    <ul class="text-sm list-disc pl-5">
                                                        @if(!empty($report['scanned_qr_codes']))
                                                            @foreach($report['scanned_qr_codes'] as $qrCode)
                                                                <li>{{ basename(parse_url($qrCode, PHP_URL_PATH)) }}</li>
                                                            @endforeach
                                                        @else
                                                            <li class="text-gray-500">Tidak ada QR code discan</li>
                                                        @endif
                                                    </ul>
                                                    <p class="text-sm font-semibold mt-2">QR Code Belum Discan ({{ count($report['unscanned_qr_codes'] ?? []) }}):</p>
                                                    <ul class="text-sm list-disc pl-5">
                                                        @if(!empty($report['unscanned_qr_codes']))
                                                            @foreach($report['unscanned_qr_codes'] as $qrCode)
                                                                <li>{{ $qrCode }}</li>
                                                            @endforeach
                                                        @else
                                                            <li class="text-gray-500">Semua QR code telah discan</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="p-3 border border-gray-300">
                                                <form action="{{ route('inventory.delete_report', $index) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                        <table class="w-full border-collapse text-sm">
                            <tr class="font-bold bg-gray-200">
                                <td class="p-3 border border-gray-300" colspan="4">Total</td>
                                <td class="p-3 border border-gray-300">{{ $totalSystemStock }}</td>
                                <td class="p-3 border border-gray-300">{{ $totalPhysicalStock }}</td>
                                <td class="p-3 border border-gray-300 {{ $totalDifference < 0 ? 'text-red-500' : ($totalDifference > 0 ? 'text-yellow-500' : 'text-green-500') }}">
                                    {{ $totalDifference > 0 ? '+' : '' }}{{ $totalDifference }}
                                </td>
                                <td class="p-3 border border-gray-300" colspan="2"></td>
                            </tr>
                        </table>
                        <div class="mt-4 text-gray-800">
                            <p class="text-sm">
                                Keterangan:
                                <br>Total stok sistem dari laporan: <strong>{{ $totalSystemStock }} unit</strong>.
                                <br>Total stok fisik dari laporan: <strong>{{ $totalPhysicalStock }} unit</strong>.
                                <br>Selisih stok laporan: <strong>{{ $totalDifference >= 0 ? '+' : '' }}{{ $totalDifference }} unit</strong>.
                                @if(isset($totalStock))
                                    <br>Total stok di inventory: <strong>{{ $totalStock }} unit</strong>.
                                    @if($totalSystemStock == $totalStock && $totalPhysicalStock == $totalStock)
                                        <span class="text-green-500 font-semibold">Stok laporan sesuai dengan inventory.</span>
                                    @else
                                        @php
                                            $systemStockDifference = $totalSystemStock - $totalStock;
                                            $physicalStockDifference = $totalPhysicalStock - $totalStock;
                                        @endphp
                                        @if($systemStockDifference < 0)
                                            <span class="text-red-500 font-semibold">Produk kurang {{ abs($systemStockDifference) }} unit dibandingkan inventory.</span>
                                        @elseif($systemStockDifference > 0)
                                            <span class="text-yellow-500 font-semibold">Produk lebih {{ $systemStockDifference }} unit dibandingkan inventory.</span>
                                        @else
                                            <span class="text-green-500 font-semibold">Stok sistem sesuai dengan inventory.</span>
                                        @endif
                                        @if($physicalStockDifference != 0)
                                            <br>
                                            @if($physicalStockDifference < 0)
                                                <span class="text-red-500 font-semibold">Stok fisik kurang {{ abs($physicalStockDifference) }} unit dibandingkan inventory.</span>
                                            @else
                                                <span class="text-yellow-500 font-semibold">Stok fisik lebih {{ $physicalStockDifference }} unit dibandingkan inventory.</span>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <span class="text-red-500 font-semibold">Data stok inventory tidak tersedia.</span>
                                @endif
                            </p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 text-sm">Belum ada laporan stock opname.</p>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow mb-4 animate-fade-in" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow mb-4 animate-fade-in" role="alert">
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
    .barcode-input {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        top: -9999px;
        left: -9999px;
    }
    .scanned-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        margin-bottom: 8px;
        background-color: #f87171;
        color: white;
        border-radius: 6px;
        font-size: 14px;
    }
    .scanned-item.new {
        background-color: #34d399;
    }
    .scanned-item button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
    }
    #products-table table, #reports-table table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    #products-table th, #products-table td, #reports-table th, #reports-table td {
        padding: 12px;
        border: 1px solid #e5e7eb;
        text-align: left;
    }
    #products-table th, #reports-table th {
        background-color: #f3f4f6;
        font-weight: 600;
        color: #1f2937;
    }
    #products-table input {
        background-color: #f3f4f6;
        color: #1f2937;
        padding: 6px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        width: 80px;
        font-size: 14px;
    }
    #save-report {
        display: none;
        background-color: #16a34a;
        border: 2px solid #14532d;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.3s ease;
    }
    #save-report:not(.hidden) {
        display: inline-block;
    }
    #save-report:hover {
        background-color: #15803d;
        transform: translateY(-2px);
    }
    .qr-codes-details {
        background-color: #f9fafb;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }
    .qr-codes-details ul {
        max-height: 150px;
        overflow-y: auto;
    }
    .toggle-qr-codes {
        cursor: pointer;
    }
    #search-results .search-result-item {
        padding: 8px;
        cursor: pointer;
        border-bottom: 1px solid #e5e7eb;
    }
    #search-results .search-result-item:hover {
        background-color: #f3f4f6;
    }
    @media (max-width: 640px) {
        #products-table table, #reports-table table {
            font-size: 12px;
        }
        #products-table th, #products-table td, #reports-table th, #reports-table td {
            padding: 8px;
        }
        #products-table input {
            width: 60px;
        }
        #save-report {
            font-size: 14px;
            padding: 10px 20px;
        }
        #reports-table h3 {
            font-size: 14px;
        }
        .qr-codes-details {
            font-size: 12px;
        }
        #product-search {
            font-size: 14px;
        }
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previousPhysicalStocks = @json($previousPhysicalStocks ?? []);
    const startScannerBtn = document.getElementById('start-scanner');
    const stopScannerBtn = document.getElementById('stop-scanner');
    const scannerContainer = document.getElementById('scanner-container');
    const barcodeInput = document.getElementById('barcode-input');
    const productsTable = document.getElementById('products-table');
    const saveReportBtn = document.getElementById('save-report');
    const reportsTable = document.getElementById('reports-table');
    const productSearch = document.getElementById('product-search');
    const searchResults = document.getElementById('search-results');
    const scannedList = document.createElement('div');
    scannerContainer.parentNode.insertBefore(scannedList, scannerContainer.nextSibling);
    scannedList.className = 'mt-4';

    let html5QrCode = null;
    let scannedProducts = {};
    let scannedQRCodes = new Set();
    let scanTimeout = null;
    let lastScanTime = 0;
    let searchTimeout = null;

    // Reset state saat halaman dimuat
    scannedProducts = {};
    scannedQRCodes.clear();
    updateProductsTable();

    // Event delegation untuk tombol toggle-qr-codes
    reportsTable.addEventListener('click', function(event) {
        const button = event.target.closest('.toggle-qr-codes');
        if (button) {
            const index = button.dataset.index;
            const details = document.querySelector(`.qr-codes-details[data-index="${index}"]`);
            if (details) {
                console.log('Toggling QR codes for index:', index);
                details.classList.toggle('hidden');
                button.textContent = details.classList.contains('hidden') ? 'Lihat QR Codes' : 'Sembunyikan QR Codes';
            } else {
                console.warn('QR codes details not found for index:', index);
            }
        }
    });

    // Real-time product search
    productSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = this.value.trim();
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }
            fetch(`/inventory/search?search=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (data.products.length === 0) {
                    searchResults.innerHTML = '<div class="p-4 text-gray-500">Tidak ada produk ditemukan</div>';
                    searchResults.classList.remove('hidden');
                    return;
                }
                data.products.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = `${product.name} (${product.size}, ${product.color})`;
                    div.dataset.productId = product.id;
                    div.addEventListener('click', () => {
                        addManualProduct(product.id);
                        searchResults.classList.add('hidden');
                        productSearch.value = '';
                    });
                    searchResults.appendChild(div);
                });
                searchResults.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error searching products:', error);
                showAlert('error', 'Gagal mencari produk: ' + error.message);
            });
        }, 300);
    });

    // Klik di luar search results untuk menutup
    document.addEventListener('click', function(event) {
        if (!searchResults.contains(event.target) && event.target !== productSearch) {
            searchResults.classList.add('hidden');
        }
    });

    startScannerBtn.addEventListener('click', async () => {
        try {
            console.log('Starting scanner, resetting state...');
            scannedProducts = {};
            scannedQRCodes.clear();
            updateProductsTable();
            scannerContainer.classList.remove('hidden');
            startScannerBtn.classList.add('hidden');
            barcodeInput.focus();

            const permission = await navigator.permissions.query({ name: 'camera' });
            if (permission.state !== 'denied') {
                html5QrCode = new Html5Qrcode("qr-scanner");
                const cameras = await Html5Qrcode.getCameras();
                if (cameras.length === 0) {
                    showAlert('warning', 'Tidak ada kamera yang ditemukan. Gunakan scanner fisik 2D.');
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
            } else {
                showAlert('warning', 'Izin kamera ditolak. Gunakan scanner fisik 2D.');
            }
        } catch (err) {
            console.error('Error starting QR scanner:', err);
            showAlert('error', 'Gagal memulai scanner: ' + err.message);
            resetScanner();
        }
    });

    barcodeInput.addEventListener('input', function(e) {
        const decodedText = e.target.value.trim();
        if (decodedText) {
            clearTimeout(scanTimeout);
            scanTimeout = setTimeout(() => {
                onScanSuccess(decodedText);
                barcodeInput.value = '';
                barcodeInput.focus();
            }, 500);
        }
    });

    barcodeInput.addEventListener('blur', () => {
        if (!scannerContainer.classList.contains('hidden')) {
            setTimeout(() => barcodeInput.focus(), 100);
        }
    });

    stopScannerBtn.addEventListener('click', () => {
        resetScanner();
    });

    function onScanSuccess(decodedText) {
        const currentTime = Date.now();
        if (currentTime - lastScanTime < 500) {
            console.log('Scan ignored: too soon after last scan');
            return;
        }
        lastScanTime = currentTime;

        console.log('Scanned QR:', decodedText, 'ScannedQRCodes:', [...scannedQRCodes]);

        validateQrCode(decodedText).then(response => {
            if (!response.valid) {
                showAlert('error', response.message);
                return;
            }

            const productId = response.product_id;
            const unitCode = response.unit_code;

            const isNewScan = !scannedQRCodes.has(decodedText);
            if (isNewScan) {
                scannedQRCodes.add(decodedText);
                addScannedItem(decodedText, true);

                if (!scannedProducts[productId]) {
                    scannedProducts[productId] = {
                        count: previousPhysicalStocks[productId] || 0,
                        systemStock: 0,
                        name: 'Memuat...',
                        size: '-',
                        color: '-',
                        qrCodes: new Set(),
                        brand: '',
                        model: ''
                    };
                    fetchProductData(productId);
                }
                scannedProducts[productId].count++;
                scannedProducts[productId].qrCodes.add(decodedText);
                updateProductsTable();
                showAlert('success', `QR code dipindai untuk produk ID ${productId} (Unit: ${unitCode}).`);
            } else {
                console.log('Duplicate scan detected:', decodedText);
                if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
                    addScannedItem(decodedText, false);
                    showAlert('warning', 'QR code ini sudah dipindai dalam sesi ini.');
                }
            }
        }).catch(error => {
            console.error('Error validating QR code:', error);
            showAlert('error', 'Gagal memvalidasi QR code: ' + error.message);
        });
    }

    function validateQrCode(qrCode) {
        return fetch('{{ route('inventory.validate_qr_code') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Validasi gagal');
                });
            }
            return response.json();
        });
    }

    function onScanError(errorMessage) {
        console.warn('QR scan error:', errorMessage);
    }

    function addManualProduct(productId) {
        if (!scannedProducts[productId]) {
            scannedProducts[productId] = {
                count: previousPhysicalStocks[productId] || 0,
                systemStock: 0,
                name: 'Memuat...',
                size: '-',
                color: '-',
                qrCodes: new Set(),
                brand: '',
                model: ''
            };
            fetchProductData(productId);
        }
        scannedProducts[productId].count++;
        updateProductsTable();
        showAlert('success', `Produk ID ${productId} ditambahkan secara manual.`);
    }

    function fetchProductData(productId) {
        console.log('Fetching product data for ID:', productId);
        fetch(`/inventory/${productId}/json`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Fetch response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched product data:', data);
            if (data.error) {
                showAlert('error', data.error);
                scannedProducts[productId].name = 'Produk Tidak Ditemukan';
                scannedProducts[productId].size = 'N/A';
                scannedProducts[productId].color = 'N/A';
                scannedProducts[productId].systemStock = 0;
                scannedProducts[productId].brand = 'Unknown';
                scannedProducts[productId].model = 'Unknown';
            } else {
                scannedProducts[productId] = {
                    ...scannedProducts[productId],
                    name: data.name || 'Tidak Diketahui',
                    size: data.size || '-',
                    color: data.color || '-',
                    systemStock: data.stock || 0,
                    brand: data.brand || 'Unknown',
                    model: data.model || 'Unknown'
                };
            }
            updateProductsTable();
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            showAlert('error', 'Gagal mengambil data produk: ' + error.message);
            scannedProducts[productId].name = 'Gagal Memuat';
            scannedProducts[productId].size = 'N/A';
            scannedProducts[productId].color = 'N/A';
            scannedProducts[productId].systemStock = 0;
            scannedProducts[productId].brand = 'Unknown';
            scannedProducts[productId].model = 'Unknown';
            updateProductsTable();
        });
    }

    function updateProductsTable() {
        console.log('Updating table with products:', scannedProducts);
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
                statusClass = 'text-green-500';
            } else if (difference > 0) {
                statusMessage = `Lebih ${difference}`;
                statusClass = 'text-yellow-500';
            } else {
                statusMessage = `Kurang ${Math.abs(difference)}`;
                statusClass = 'text-red-500';
            }
            html += `
                <tr class="hover:bg-gray-100">
                    <td>${product.name}</td>
                    <td>${product.size}</td>
                    <td>${product.color}</td>
                    <td>${product.systemStock}</td>
                    <td>
                        <input type="number" min="0" value="${product.count}" data-product-id="${productId}" class="physical-stock-input">
                        ${previousPhysicalStocks[productId] ? `<span class="text-gray-500 text-xs block mt-1">(Sebelumnya: ${previousPhysicalStocks[productId]})</span>` : ''}
                    </td>
                    <td class="${statusClass}">${statusMessage}</td>
                    <td>
                        <button class="update-stock-btn bg-orange-500 text-white px-2 py-1 rounded hover:bg-orange-600 transition-colors font-semibold" data-product-id="${productId}">Update</button>
                        <button class="remove-product-btn bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition-colors font-semibold ml-2" data-product-id="${productId}">Hapus</button>
                    </td>
                </tr>
            `;
        }
        html += `
                </tbody>
            </table>
        `;
        productsTable.innerHTML = html;

        console.log('Scanned products count:', Object.keys(scannedProducts).length, 'Save report button hidden:', saveReportBtn.classList.contains('hidden'));
        saveReportBtn.classList.toggle('hidden', Object.keys(scannedProducts).length === 0);
        if (Object.keys(scannedProducts).length > 0) {
            saveReportBtn.style.display = 'inline-block';
        } else {
            saveReportBtn.style.display = 'none';
        }

        document.querySelectorAll('.physical-stock-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const newValue = parseInt(this.value) || 0;
                if (newValue < 0) {
                    showAlert('error', 'Stok fisik tidak boleh negatif.');
                    this.value = scannedProducts[productId].count;
                    return;
                }
                scannedProducts[productId].count = newValue;
                updateProductsTable();
            });
        });

        document.querySelectorAll('.update-stock-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                updateStock(productId);
            });
        });

        document.querySelectorAll('.remove-product-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                scannedQRCodes = new Set([...scannedQRCodes].filter(qr => !qr.includes(`/inventory/${productId}`)));
                delete scannedProducts[productId];
                updateProductsTable();
                scannedList.innerHTML = '';
                [...scannedQRCodes].forEach(qr => addScannedItem(qr, false));
                showAlert('success', `Produk ID ${productId} dihapus dari daftar.`);
            });
        });
    }

    function updateStock(productId) {
        const formData = new FormData();
        formData.append('physical_stock', scannedProducts[productId].count);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/inventory/${productId}/physical-stock`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Update stock response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Update stock response:', data);
            if (data.success) {
                showAlert('success', data.message);
                scannedQRCodes = new Set([...scannedQRCodes].filter(qr => !qr.includes(`/inventory/${productId}`)));
                delete scannedProducts[productId];
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

    saveReportBtn.addEventListener('click', () => {
        console.log('Save report button clicked, reports:', scannedProducts);
        const report = Object.entries(scannedProducts).map(([productId, product]) => ({
            product_id: productId,
            name: product.name,
            size: product.size,
            color: product.color,
            system_stock: product.systemStock,
            physical_stock: product.count,
            difference: product.count - product.systemStock,
            scanned_qr_codes: Array.from(product.qrCodes),
            brand: product.brand,
            model: product.model
        }));

        if (report.length === 0) {
            showAlert('error', 'Tidak ada produk untuk disimpan dalam laporan.');
            return;
        }

        const totalPhysicalStock = report.reduce((sum, item) => sum + item.physical_stock, 0);
        const totalSystemStock = report.reduce((sum, item) => sum + item.system_stock, 0);
        const totalDifference = totalPhysicalStock - totalSystemStock;

        fetch('{{ route('inventory.save_report') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ reports: report })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const message = `Laporan berhasil disimpan! ${report.length} produk dilaporkan. Total stok fisik: ${totalPhysicalStock} unit, stok sistem: ${totalSystemStock} unit, selisih: ${totalDifference >= 0 ? '+' : ''}${totalDifference} unit.`;
                showAlert('success', message);
                resetScanner();
                window.location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving report:', error);
            showAlert('error', 'Gagal menyimpan laporan: ' + error.message);
        });
    });

    function resetScanner() {
        console.log('Resetting scanner...');
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
                saveReportBtn.style.display = 'none';
                barcodeInput.blur();
                searchResults.classList.add('hidden');
                console.log('Scanner reset complete');
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
            saveReportBtn.style.display = 'none';
            barcodeInput.blur();
            searchResults.classList.add('hidden');
            console.log('Scanner reset complete (no html5QrCode)');
        }
    }

    function showAlert(type, message) {
        console.log(`Showing ${type} alert: ${message}`);
        const alertDiv = document.createElement('div');
        alertDiv.className = `bg-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-100 border border-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-400 text-${type === 'success' ? 'green' : type === 'warning' ? 'yellow' : 'red'}-700 px-4 py-3 rounded-lg shadow mb-4 animate-fade-in`;
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

    function addScannedItem(decodedText, isNew) {
        if (!scannedList.querySelector(`[data-qr="${decodedText}"]`)) {
            console.log('Adding scanned item:', decodedText, 'Is new:', isNew);
            const item = document.createElement('div');
            item.className = `scanned-item ${isNew ? 'new' : ''}`;
            item.setAttribute('data-qr', decodedText);
            item.innerHTML = `
                ${isNew ? 'QR code dipindai.' : 'QR code ini sudah dipindai.'}
                <button onclick="this.parentElement.remove()">×</button>
            `;
            scannedList.appendChild(item);

            if (isNew) {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => item.remove(), 500);
                }, 3000);
            }

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
        } else {
            console.log('Item already in scannedList:', decodedText);
        }
    }
});
</script>
@endsection