@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">
            Scan QR Code
        </h1>

        <div class="bg-white p-4 md:p-6 rounded-lg shadow">
            <h2 class="text-lg md:text-xl font-semibold mb-4">Pindai QR Code Produk</h2>
            <p class="text-gray-600 mb-4">Gunakan kamera perangkat atau scanner fisik untuk memindai QR code produk. Pastikan QR code mengarah ke halaman verifikasi produk.</p>

            <!-- QR Scanner Container -->
            <div id="qr-reader" class="w-full max-w-md mx-auto mb-4 border border-gray-300 rounded-lg"></div>
            <div id="qr-result" class="text-center text-gray-700 mb-4 hidden">
                <p class="font-medium">Hasil Pemindaian:</p>
                <p id="qr-result-text" class="text-sm"></p>
            </div>

            <!-- Manual Input for Physical Scanner -->
            <div class="mt-4">
                <label for="qr-input" class="block text-sm font-medium text-gray-700">Masukkan Kode QR (Scanner Fisik)</label>
                <input type="text" id="qr-input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" placeholder="Tempel atau ketik URL QR code">
                <button id="submit-qr" class="mt-2 bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">Proses</button>
            </div>

            <!-- Session Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4 animate-slide-in" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 animate-slide-in" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button type="button" class="absolute top-0 right-0 mt-3 mr-4" onclick="this.parentElement.remove()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-in {
        animation: slideIn 0.3s ease-in-out;
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrReader = new Html5Qrcode("qr-reader");
        const qrResult = document.getElementById('qr-result');
        const qrResultText = document.getElementById('qr-result-text');
        const qrInput = document.getElementById('qr-input');
        const submitQrButton = document.getElementById('submit-qr');

        // Get the expected path pattern
        const validPathPattern = /\/inventory\/\d+\/verify/;

        // Start QR code scanner
        qrReader.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                console.log('QR Code decoded:', decodedText); // Debugging
                qrResultText.textContent = decodedText;
                qrResult.classList.remove('hidden');
                redirectToVerify(decodedText);
            },
            (error) => {
                console.warn('QR scan error:', error);
                qrResultText.textContent = 'Gagal memindai QR code. Coba lagi atau gunakan scanner fisik.';
                qrResult.classList.remove('hidden');
            }
        ).catch(err => {
            console.error('Failed to start QR scanner:', err);
            qrResultText.textContent = 'Gagal memulai pemindai QR. Pastikan izin kamera aktif atau gunakan scanner fisik.';
            qrResult.classList.remove('hidden');
        });

        // Handle manual QR input (physical scanner)
        submitQrButton.addEventListener('click', () => {
            const qrText = qrInput.value.trim();
            if (qrText) {
                console.log('Manual input:', qrText); // Debugging
                qrResultText.textContent = qrText;
                qrResult.classList.remove('hidden');
                redirectToVerify(qrText);
            } else {
                qrResultText.textContent = 'Masukkan URL QR code terlebih dahulu.';
                qrResult.classList.remove('hidden');
            }
        });

        // Redirect to verification page
        function redirectToVerify(url) {
            // Normalize URL by removing trailing slashes and extra spaces
            url = url.trim().replace(/\/+$/, '');

            // Check if URL path matches the expected pattern
            if (validPathPattern.test(url)) {
                // Extract product ID from URL
                const match = url.match(/\/inventory\/(\d+)\/verify/);
                if (match && match[1]) {
                    // Check if product exists via AJAX
                    fetch(`{{ url('/inventory') }}/${match[1]}/json`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                qrResultText.textContent = 'Produk tidak ditemukan!';
                                qrResult.classList.remove('hidden');
                            } else {
                                // Construct the correct URL using the current domain
                                const redirectUrl = `{{ url('/inventory') }}/${match[1]}/verify`;
                                console.log('Redirecting to:', redirectUrl); // Debugging
                                window.location.href = redirectUrl;
                            }
                        })
                        .catch(() => {
                            qrResultText.textContent = 'Gagal memvalidasi produk. Coba lagi.';
                            qrResult.classList.remove('hidden');
                        });
                } else {
                    qrResultText.textContent = 'URL QR code tidak valid!';
                    qrResult.classList.remove('hidden');
                }
            } else {
                qrResultText.textContent = 'QR code tidak mengarah ke halaman verifikasi! Pastikan URL mengandung /inventory/{id}/verify';
                qrResult.classList.remove('hidden');
            }
        }

        // Handle alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endsection