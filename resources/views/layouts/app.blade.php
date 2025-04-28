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
    <div class="min-h-screen bg-app-image">
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
</body>
</html>