@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Add New Employee</h1>

    <form action="{{ route('owner.employee-accounts.store') }}" method="POST" class="bg-white shadow rounded p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-gray-700 font-medium mb-2">Name</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" name="password" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div class="flex justify-end">
            <button class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
        </div>
    </form>
</div>
@endsection
