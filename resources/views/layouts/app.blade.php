<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> 
<head>     
    <meta charset="utf-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1">     
    <meta name="csrf-token" content="{{ csrf_token() }}">      
    <title>{{ config('app.name', 'Toko Sepatu By Sovan') }}</title>      
    <!-- Fonts -->     
    <link rel="preconnect" href="https://fonts.bunny.net">     
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />      
    <!-- Scripts -->     
    @vite(['resources/css/app.css', 'resources/js/app.js'])      
    <!-- Custom Styles -->     
    <style>         
        .bg-app-image {             
            background-image: url('{{ asset('images/bgapp.jpg') }}');             
            background-size: cover;             
            background-attachment: fixed;             
            background-position: center;         
        }
    </style> 
</head> 
<body class="font-sans antialiased">     
    <div class="min-h-screen flex flex-col justify-between bg-app-image">         
        <div>             
            @include('layouts.navigation')              
            <!-- Page Heading -->             
            @if (isset($header))                 
                <header class="bg-white bg-opacity-90 shadow">                     
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">                         
                        {{ $header }}                     
                    </div>                 
                </header>             
            @endif              
            <!-- Page Content -->             
            <main>                 
                @yield('content')             
            </main>         
        </div>          
        <!-- Footer -->         
        <footer class="bg-gradient-to-b from-gray-900 to-[#1E1E1E] text-white mt-8">
            <div class="max-w-4xl mx-auto px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <!-- Brand -->
                    <div class="text-center md:text-left mb-2 md:mb-0">
                        <h2 class="text-lg font-bold mb-1">Toko Sepatu By Sovan</h2>
                        <p class="text-gray-400 text-xs">
                            Solusi Smart System berkualitas dengan layanan terbaik.
                            Terintegrasi dengan sistem inventory modern.
                        </p>
                    </div>

                    <!-- Contact -->
                    <div class="text-center md:text-right">
                        <div class="flex items-center justify-center md:justify-end mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-xs">Jika ada kendala dan butuh bantuan:</span>
                        </div>
                        <p class="text-base font-bold text-white mb-1">082241992151</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-800 my-2"></div>

                <!-- Bottom -->
                <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                    <p class="text-xs text-gray-400">
                        &copy; {{ date('Y') }} Toko Sepatu By Sovan. All rights reserved.
                    </p>
                    <div class="mt-1 md:mt-0 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="text-gray-300 text-xs font-medium">Made by Tim Capstone UNDIP</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>