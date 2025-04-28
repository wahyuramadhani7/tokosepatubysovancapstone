@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Dashboard Pemilik Toko</h1>
    <p>Selamat datang, {{ Auth::user()->name }}! Anda login sebagai pemilik toko.</p>
</div>
@endsection