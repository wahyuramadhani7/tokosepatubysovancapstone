<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEPATU BY SOVAN - Login (Cabang Kudus)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 8px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 16px rgba(249, 115, 22, 0.5); }
        }
        
        @keyframes slideInDown {
            0% { transform: translateY(-40px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideInUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeInScale {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.04); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }
        
        @keyframes ripple {
            0% { transform: scale(0); opacity: 0.5; }
            100% { transform: scale(3); opacity: 0; }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .float { animation: float 4s ease-in-out infinite; }
        .glow { animation: glow 1.5s ease-in-out infinite alternate; }
        .slide-in-down { animation: slideInDown 0.5s ease-out; }
        .slide-in-up { animation: slideInUp 0.4s ease-out; animation-delay: 0.1s; animation-fill-mode: both; }
        .fade-in-scale { animation: fadeInScale 0.3s ease-out; animation-delay: 0.2s; animation-fill-mode: both; }
        
        .input-focus-effect {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .input-focus-effect:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2);
        }
        
        .button-hover-effect {
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .button-hover-effect:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(249, 115, 22, 0.3);
        }
        
        .button-hover-effect:active { transform: translateY(0); }
        
        .ripple-effect { position: relative; overflow: hidden; }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: scale(0);
            animation: ripple 0.5s linear;
            pointer-events: none;
        }
        
        .loading-spinner {
            display: none;
            width: 14px;
            height: 14px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        .shake-error { animation: shake 0.4s ease-in-out; }
        
        .background-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(249, 115, 22, 0.2);
            border-radius: 50%;
            animation: float 5s ease-in-out infinite;
        }

        @media (max-width: 640px) {
            .container { max-width: 90vw; }
            .logo { width: 5rem; height: 5rem; }
            .text-3xl { font-size: 1.5rem; }
            .text-base { font-size: 0.875rem; }
            .text-sm { font-size: 0.75rem; }
            .py-3 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .px-4 { padding-left: 0.75rem; padding-right: 0.75rem; }
            .mb-6 { margin-bottom: 1rem; }
        }
    </style>
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center relative overflow-hidden" style="background-image: url('./images/bglogin.png')">
    <!-- Background Particles -->
    <div class="background-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 0.3s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 0.6s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 0.9s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 1.2s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 1.5s;"></div>
    </div>

    <div class="bg-gray-900 container w-full max-w-xs p-6 rounded-xl shadow-lg text-center slide-in-down backdrop-blur-md bg-opacity-90 sm:max-w-sm sm:p-8 md:max-w-md">
        <div class="mb-4">
            <div class="logo w-20 h-20 bg-blue-900 rounded-full flex items-center justify-center mx-auto float glow transition-all duration-300 hover:scale-105 sm:w-24 sm:h-24">
                <div class="text-center">
                    <span class="text-orange-500 font-bold text-xs sm:text-sm">SEPATU BY SOVAN</span>
                    <p class="text-orange-300 text-[0.6rem] mt-0.5 sm:text-xs">Cabang Kudus</p>
                </div>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-orange-500 mb-1 slide-in-up sm:text-3xl">LOGIN</h1>
        <p class="text-sm text-orange-300 mb-4 slide-in-up sm:text-base">Cabang Kudus - Welcome back!</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="fade-in-scale">
            @csrf
            <div class="mb-4 text-left sm:mb-5">
                <label for="username" class="block text-sm text-orange-500 mb-1 font-medium transition-all duration-200 sm:text-base">Username</label>
                <input type="text" id="username" name="email" required
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 input-focus-effect text-sm sm:px-4 sm:py-2.5 sm:text-base">
            </div>

            <div class="mb-4 text-left relative sm:mb-5">
                <label for="password" class="block text-sm text-orange-500 mb-1 font-medium transition-all duration-200 sm:text-base">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 input-focus-effect text-sm sm:px-4 sm:py-2.5 sm:text-base">
                <button type="button" id="togglePassword" class="absolute right-2 top-8 text-orange-500 hover:text-orange-600 transition-all duration-200 hover:scale-105 sm:right-3 sm:top-9">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4 flex items-center text-left sm:mb-5">
                <input type="checkbox" id="rememberMe" name="rememberMe" class="mr-1 h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-200 rounded sm:h-5 sm:w-5">
                <label for="rememberMe" class="text-sm text-orange-500 font-medium sm:text-base">Remember me</label>
            </div>

            <div class="mb-4 text-left sm:mb-5">
                <p class="text-sm text-orange-400 font-medium sm:text-base">
                    Lupa sandi? 
                    <a href="https://wa.me/6282241992151?text=Hallo%20mas%20Wahyu%20saya%20lupa%20kata%20sandi" target="_blank" class="text-orange-500 hover:text-orange-300 underline font-semibold transition-all duration-200 hover:scale-105 inline-block">
                        Klik disini
                    </a>
                </p>
            </div>

            <button type="submit" id="loginButton"
                class="w-full bg-orange-500 text-white py-2 rounded-lg font-bold text-base button-hover-effect ripple-effect flex items-center justify-center mb-2 sm:py-2.5 sm:text-lg">
                <span id="buttonText">LOGIN</span>
                <div class="loading-spinner ml-1 sm:ml-2"></div>
            </button>
        </form>

        <div id="errorPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white p-4 rounded-lg shadow-lg text-center fade-in-scale max-w-xs w-full sm:max-w-sm sm:p-6">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:w-14 sm:h-14">
                        <svg class="w-6 h-6 text-red-500 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p id="errorMessage" class="text-red-600 font-bold mb-3 text-sm sm:text-base">Authentication Failed!</p>
                    <button id="closePopup" class="bg-orange-500 text-white px-4 py-1 rounded-lg hover:bg-orange-600 transition-all duration-200 button-hover-effect text-sm sm:px-6 sm:py-2 sm:text-base">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const errorPopup = document.getElementById('errorPopup');
        const errorMessage = document.getElementById('errorMessage');
        const closePopup = document.getElementById('closePopup');
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.getElementById('buttonText');
        const rememberMe = document.getElementById('rememberMe');
        const loadingSpinner = document.querySelector('.loading-spinner');

        // Load saved credentials if they exist
        window.onload = function() {
            const savedUsername = localStorage.getItem('savedUsername');
            const savedPassword = localStorage.getItem('savedPassword');
            const savedRememberMe = localStorage.getItem('rememberMe');

            if (savedUsername && savedPassword && savedRememberMe === 'true') {
                usernameInput.value = savedUsername;
                passwordInput.value = savedPassword;
                rememberMe.checked = true;
            }

            // Check for server-side errors
            @if ($errors->has('email') || $errors->has('password'))
                errorMessage.textContent = 'Password Salah!';
                errorPopup.classList.remove('hidden');
                document.querySelector('.bg-gray-900').classList.add('shake-error');
                setTimeout(() => {
                    document.querySelector('.bg-gray-900').classList.remove('shake-error');
                }, 400);
            @endif

            // Add floating animation to particles
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDuration = (Math.random() * 4 + 3) + 's';
            });
        };

        // Ripple effect function
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
            circle.classList.add('ripple');

            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
        }

        // Add ripple effect to buttons
        [loginButton, closePopup].forEach(button => {
            button.addEventListener('click', createRipple);
        });

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            togglePassword.style.animation = 'pulse 0.2s ease-in-out';
            setTimeout(() => {
                togglePassword.style.animation = '';
            }, 200);
            
            togglePassword.innerHTML = type === 'password' ?
                `<svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>` :
                `<svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5m0 0L21 21"></path>
                </svg>`;
        });

        loginForm.addEventListener('submit', (e) => {
            console.log('Form submitted');
            
            if (rememberMe.checked) {
                localStorage.setItem('savedUsername', usernameInput.value);
                localStorage.setItem('savedPassword', passwordInput.value);
                localStorage.setItem('rememberMe', 'true');
            } else {
                localStorage.removeItem('savedUsername');
                localStorage.removeItem('savedPassword');
                localStorage.setItem('rememberMe', 'false');
            }

            buttonText.textContent = 'LOADING...';
            loadingSpinner.style.display = 'block';
            loginButton.disabled = true;
            loginButton.classList.add('opacity-75');
        });

        closePopup.addEventListener('click', () => {
            errorPopup.classList.add('hidden');
        });

        [usernameInput, passwordInput].forEach(input => {
            input.addEventListener('focus', () => {
                input.parentNode.querySelector('label').classList.add('text-orange-400', 'font-semibold');
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentNode.querySelector('label').classList.remove('text-orange-400', 'font-semibold');
                }
            });
        });
    </script>
</body>
</html>