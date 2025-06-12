@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 bg-orange-500 text-black py-2 px-4 rounded inline-block">
            Scan QR Code
        </h1>

        <div class="bg-white p-4 md:p-6 rounded-lg shadow">
            <div class="mb-4">
                <p class="text-gray-600">Gunakan kamera untuk memindai QR code pada produk.</p>
            </div>

            <div class="flex justify-center">
                <video id="video" width="100%" height="auto" class="max-w-md rounded-lg"></video>
            </div>

            <div id="result" class="mt-4 text-center text-gray-700"></div>

            <div class="flex justify-center mt-4">
                <button id="startButton" class="bg-orange-500 text-black px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors">Mulai Scan</button>
                <button id="stopButton" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors hidden ml-3">Hentikan Scan</button>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4 animate-slide-in" role="alert">
                <span class="block sm:inline font-semibold">{{ session('success') }}</span>
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

<style>
    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-in {
        animation: slideIn 0.3s ease-in-out;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        const result = document.getElementById('result');
        let stream = null;

        startButton.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = stream;
                video.play();
                startButton.classList.add('hidden');
                stopButton.classList.remove('hidden');
                scanQRCode();
            } catch (err) {
                result.innerHTML = 'Gagal mengakses kamera: ' + err.message;
                result.classList.add('text-red-600');
            }
        });

        stopButton.addEventListener('click', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
                startButton.classList.remove('hidden');
                stopButton.classList.add('hidden');
                result.innerHTML = '';
            }
        });

        function scanQRCode() {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        console.log('QR Code detected:', code.data); // Debugging
                        // Check if the scanned URL matches the expected inventory verify route
                        const verifyUrlPattern = /\/inventory\/(\d+)\/verify\/?$/;
                        const match = code.data.match(verifyUrlPattern);
                        if (match && match[1]) {
                            result.innerHTML = 'QR Code valid, mengarahkan ke halaman verifikasi...';
                            result.classList.remove('text-red-600');
                            result.classList.add('text-green-600');
                            window.location.href = code.data; // Redirect to the verification page
                            return;
                        } else {
                            result.innerHTML = 'QR Code tidak valid untuk verifikasi stok: ' + code.data;
                            result.classList.add('text-red-600');
                        }
                    } else {
                        result.innerHTML = 'Memindai QR code...';
                    }
                }
                if (video.srcObject) {
                    requestAnimationFrame(tick);
                }
            }

            requestAnimationFrame(tick);
        }

        // Auto-dismiss alerts
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