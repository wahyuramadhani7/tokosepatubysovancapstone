<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEPATU BY SOVAN - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center" style="background-image: url('./images/bglogin.png')">
    <div class="bg-gray-900 w-full max-w-md p-8 rounded-lg shadow-lg text-center">
        <div class="mb-6">
            <div class="w-28 h-28 bg-blue-900 rounded-full flex items-center justify-center mx-auto">
                <span class="text-orange-500 font-bold text-sm">SEPATU BY SOVAN</span>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-orange-500 mb-2">LOGIN</h1>
        <p class="text-sm text-orange-500 mb-6">Welcome back! Please login to your account</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4 text-left">
                <label for="username" class="block text-sm text-orange-500 mb-1">Username</label>
                <input type="text" id="username" name="email" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div class="mb-4 text-left relative">
                <label for="password" class="block text-sm text-orange-500 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400">
                <button type="button" id="togglePassword" class="absolute right-3 top-9 text-orange-500 hover:text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <!-- Keterangan Lupa Sandi -->
            <div class="mb-6 text-left">
                <p class="text-xs text-orange-400">
                    Lupa sandi? 
                    <a href="https://wa.me/6282241992151?text=Hallo%20mas%20Wahyu%20saya%20lupa%20kata%20sandi" target="_blank" class="text-orange-500 hover:text-orange-300 underline font-semibold">
                        Klik disini
                    </a>
                </p>
            </div>

            <button type="submit"
                class="w-full bg-orange-500 text-white py-2 rounded-md font-bold text-lg hover:bg-orange-600 transition">
                LOGIN
            </button>
        </form>

        <!-- Popup untuk pesan kesalahan -->
        <div id="errorPopup" class="hidden fixed inset-0 bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <p id="errorMessage" class="text-red-600 font-bold mb-4">Password Salah!</p>
                    <button id="closePopup" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ambil elemen form dan input
        const loginForm = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const errorPopup = document.getElementById('errorPopup');
        const errorMessage = document.getElementById('errorMessage');
        const closePopup = document.getElementById('closePopup');

        // Muat data login dari memory jika ada
        window.onload = function() {
            // Removed localStorage usage as it's not supported in Claude artifacts
            // Form will work without persistent storage
            
            // Periksa apakah ada error dari server (misalnya, dari session Laravel)
            // @if ($errors->has('email') || $errors->has('password'))
            //     errorMessage.textContent = 'Password Salah!';
            //     errorPopup.classList.remove('hidden');
            // @endif
        };

        // Toggle visibilitas password
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ?
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>` :
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5m0 0L21 21"></path>
                </svg>`;
        });

        // Submit form
        loginForm.addEventListener('submit', (e) => {
            // Form akan dikirim ke server untuk validasi Laravel
            console.log('Form submitted');
        });

        // Tutup popup
        closePopup.addEventListener('click', () => {
            errorPopup.classList.add('hidden');
        });
    </script>
</body>
</html>