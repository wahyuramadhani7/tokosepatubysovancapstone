@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <!-- Header with updated design -->
        <div class="bg-black py-4 px-6">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-medium text-white">Account Management</h1>
                <button id="addAccountBtn"
                   onclick="window.location.href='{{ route('owner.employee-accounts.create') }}'" 
                   class="px-3 py-1 bg-white text-black rounded-md hover:bg-gray-100 transition duration-200 flex items-center gap-1 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>

        <!-- Success message with animation -->
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-md animate-fadeIn">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Account list section -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-3 gap-4 text-sm font-medium text-gray-600 mb-2">
                <div>Account Name</div>
                <div>Status</div>
                <div class="text-right">Actions</div>
            </div>
        </div>

        <!-- Empty state for no employees -->
        @if($employees->isEmpty())
            <div class="text-center py-16 px-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No accounts found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new account.</p>
                <div class="mt-6">
                    <a href="{{ route('owner.employee-accounts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Account
                    </a>
                </div>
            </div>
        @else
            <!-- Simplified table with alternating row colors -->
            <div class="overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach ($employees as $index => $employee)
                        <div class="hover:bg-gray-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <div class="text-sm font-medium text-black">{{ $employee->name }}</div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2 justify-end">
                                        <a href="{{ route('owner.employee-accounts.edit', $employee->id) }}" 
                                           class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('owner.employee-accounts.destroy', $employee->id) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination (if available) -->
            @if(method_exists($employees, 'links'))
                <div class="px-6 py-4">
                    {{ $employees->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    /* Orange button styling */
    #addAccountBtn {
        background-color: #FF5722;
        color: white;
    }
    #addAccountBtn:hover {
        background-color: #E64A19;
    }
</style>
@endsection