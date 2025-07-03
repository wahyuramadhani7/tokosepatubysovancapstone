@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Employee</h1>

    <form action="{{ route('owner.employee-accounts.update', $user->id) }}" method="POST" class="bg-white shadow rounded p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">New Password (leave blank to keep unchanged)</label>
            <p class="text-sm text-gray-500 mb-2">Note: The current password cannot be viewed for security reasons. You can set a new password below.</p>
            <div class="relative">
                <input type="password" name="password" id="password" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 pr-10" placeholder="Enter new password">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg id="eyeIcon" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eyeOffIcon" class="h-5 w-5 text-gray-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.163-3.825m3.162-3.15A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.662 3.175M6.647 6.647a10.025 10.025 0 0110.706 10.706M3 3l18 18" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="relative">
            <label class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 pr-10" placeholder="Confirm new password">
            <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg id="eyeIconConfirmation" class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eyeOffIconConfirmation" class="h-5 w-5 text-gray-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.163-3.825m3.162-3.15A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.662 3.175M6.647 6.647a10.025 10.025 0 0110.706 10.706M3 3l18 18" />
                </svg>
            </button>
            @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="button" id="generatePassword" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">Generate New Password</button>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('owner.employee-accounts') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update</button>
        </div>
    </form>
</div>

<script>
document.getElementById('generatePassword').addEventListener('click', function() {
    // Generate a random password (8 characters, mix of letters, numbers, and symbols)
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 8; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    // Set the password and confirmation fields
    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;
});

// Toggle visibility for password field
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
});

// Toggle visibility for password confirmation field
document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const eyeIconConfirmation = document.getElementById('eyeIconConfirmation');
    const eyeOffIconConfirmation = document.getElementById('eyeOffIconConfirmation');
    
    if (passwordConfirmationInput.type === 'password') {
        passwordConfirmationInput.type = 'text';
        eyeIconConfirmation.classList.add('hidden');
        eyeOffIconConfirmation.classList.remove('hidden');
    } else {
        passwordConfirmationInput.type = 'password';
        eyeIconConfirmation.classList.remove('hidden');
        eyeOffIconConfirmation.classList.add('hidden');
    }
});
</script>
@endsection