<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Toko Sepatu by Sovan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: #0a0e1a;
            overflow-x: hidden;
            position: relative;
        }

        /* Particle background */
        .particle-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') no-repeat center/cover;
            opacity: 0.1;
            z-index: -1;
        }

        /* Slide-in animation */
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Neon glow effect */
        @keyframes neonGlow {
            0%, 100% { box-shadow: 0 0 10px #f97316, 0 0 20px #06b6d4; }
            50% { box-shadow: 0 0 20px #f97316, 0 0 30px #06b6d4; }
        }

        .animate-slide-in-left {
            animation: slideInLeft 1s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 1.2s ease-out forwards;
        }

        .animate-neon-glow {
            animation: neonGlow 2s infinite;
        }

        /* Product card with parallax tilt */
        .product-card {
            background: rgba(10, 14, 26, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(249, 115, 22, 0.3);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: perspective(1000px) rotateX(3deg) rotateY(3deg);
        }

        /* Pulsing badges */
        .badge {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .text-6xl { font-size: 2.5rem; }
            .text-2xl { font-size: 1.5rem; }
            .text-lg { font-size: 1rem; }
            .py-16 { padding-top: 4rem; padding-bottom: 4rem; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col relative">
    <div class="particle-bg"></div>

    <!-- Header -->
    <div class="w-full py-16 text-center animate-slide-in-left">
        <div class="inline-flex items-center justify-center w-28 h-28 bg-gradient-to-r from-orange-500 to-cyan-500 rounded-full mb-4 shadow-2xl animate-neon-glow">
            <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m-4 4h16l-2 6H6l-2-6z" />
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-white mb-3 tracking-wider">Toko Sepatu by Sovan</h1>
        <p class="text-2xl text-cyan-400 animate-slide-in-right">Strut Your Style with Sovan’s Swagger!</p>
    </div>

    <!-- Product Card -->
    <div class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="max-w-lg w-full product-card rounded-3xl p-10 animate-slide-in-left">
            <h2 class="text-3xl font-bold text-white text-center mb-6">{{ $product->name ?? 'Nama Produk' }}</h2>
            <div class="flex justify-center items-center space-x-8">
                <span class="badge bg-gradient-to-r from-orange-500 to-cyan-500 text-white px-6 py-3 rounded-full text-lg font-semibold">{{ $product->size ?? 'Size' }}</span>
                <span class="badge bg-gradient-to-r from-orange-500 to-cyan-500 text-white px-6 py-3 rounded-full text-lg font-semibold">{{ $product->color ?? 'Color' }}</span>
            </div>
        </div>
    </div>

    <script>
        // Restart animations on page load
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.animate-slide-in-left, .animate-slide-in-right');
            elements.forEach(el => {
                el.style.animation = 'none';
                setTimeout(() => {
                    el.style.animation = el.classList.contains('animate-slide-in-left') 
                        ? 'slideInLeft 1s ease-out forwards' 
                        : 'slideInRight 1.2s ease-out forwards';
                }, 100);
            });
        });
    </script>
</body>
</html>