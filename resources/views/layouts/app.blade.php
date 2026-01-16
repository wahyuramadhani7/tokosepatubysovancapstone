<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Toko Sepatu By Sovan') }}</title>

    <!-- Google Font: Libre Baskerville (400, 400i, 700) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind + Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Background & Font -->
    <style>
        /* Background gambar untuk seluruh aplikasi */
        .bg-app-image {
            background-image: url('{{ asset('images/bgapp.jpg') }}');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        /* Font utama seluruh aplikasi: Libre Baskerville */
        body {
            font-family: 'Libre Baskerville', serif;
            padding-top: 4rem; /* karena navbar fixed tinggi 64px (h-16) */
        }

        /* Pastikan semua elemen di dalam main juga pakai font ini */
        .font-libre-baskerville {
            font-family: 'Libre Baskerville', serif;
        }
    </style>
</head>

<body class="antialiased bg-app-image min-h-screen flex flex-col">

    <!-- Navbar (sudah pakai Libre Baskerville) -->
    @include('layouts.navigation')

    <!-- Page Heading (opsional) -->
    @if (isset($header))
        <header class="bg-white bg-opacity-95 shadow-lg backdrop-blur-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    {{ $header }}
                </h1>
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer Elegan dengan Libre Baskerville -->
    <footer class="bg-gradient-to-b from-gray-900 to-[#1E1E1E] text-white mt-auto">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <!-- Brand -->
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <h2 class="text-2xl font-bold mb-2">Toko Sepatu By Sovan</h2>
                    <p class="text-gray-400 text-sm max-w-md">
                        Solusi Smart System berkualitas dengan layanan terbaik.<br>
                        Terintegrasi dengan sistem inventory modern.
                    </p>
                </div>

                <!-- Contact -->
                <div class="text-center md:text-right">
                    <div class="flex items-center justify-center md:justify-end mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-sm text-gray-300">Butuh bantuan?</span>
                    </div>
                    <p class="text-xl font-bold">0822-4199-2151</p>
                </div>
            </div>

            <hr class="border-gray-800 my-6">

            <div class="flex flex-col md:flex-row justify-between items-center text-sm">
                <p class="text-gray-400">
                    © {{ date('Y') }} Toko Sepatu By Sovan. All rights reserved.
                </p>
                <div class="flex items-center mt-3 md:mt-0">
                    <span class="text-gray-300 text-sm">Made with</span>
                    <svg class="h-4 w-4 mx-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-300 text-sm">by Tim Capstone UNDIP</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>