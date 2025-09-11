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
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 10px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.5); }
        }
        
        @keyframes slideInDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideInUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeInScale {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
        
        @keyframes ripple {
            0% { transform: scale(0); opacity: 0.5; }
            100% { transform: scale(3); opacity: 0; }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .float { animation: float 5s ease-in-out infinite; }
        .glow { animation: glow 1.5s ease-in-out infinite alternate; }
        .slide-in-down { animation: slideInDown 0.6s ease-out; }
        .slide-in-up { animation: slideInUp 0.5s ease-out; animation-delay: 0.1s; animation-fill-mode: both; }
        .fade-in-scale { animation: fadeInScale 0.4s ease-out; animation-delay: 0.2s; animation-fill-mode: both; }
        
        .input-focus-effect {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .input-focus-effect:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.2);
        }
        
        .button-hover-effect {
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .button-hover-effect:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
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
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
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
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center relative overflow-hidden" style="background-image: url('./images/bglogin.png')">
    <!-- Background Particles -->
    <div class="background-particles">
        <div class="particle" style="left: 15%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 25%; animation-delay: 0.5s;"></div>
        <div class="particle" style="left: 35%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 45%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 55%; animation-delay: 2s;"></div>
    </div>

    <div class="bg-gray-900 w-full max-w-sm p-6 rounded-lg shadow-md text-center slide-in-down backdrop-blur-sm bg-opacity-95 sm:max-w-md sm:p-8">
        <div class="mb-4">
            <div class="w-24 h-24 bg-blue-900 rounded-full flex items-center justify-center mx-auto float glow transition-all duration-300 hover:scale-105">
                <span class="text-orange-500 font-bold text-xs">SEPATU BY SOVAN</span>
            </div>
        </div>
        <h1 class="text-xl font-bold text-orange-500 mb-1 slide-in-up sm:text-2xl">LOGIN</h1>
        <p class="text-xs text-orange-500 mb-4 slide-in-up sm:text-sm">Welcome back! Please login to your account</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="fade-in-scale">
            @csrf
            <div class="mb-3 text-left">
                <label for="username" class="block text-xs text-orange-500 mb-1 transition-all duration-200 sm:text-sm">Username</label>
                <input type="text" id="username" name="email" required
                    class="w-full px-3 py-1.5 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 input-focus-effect text-sm sm:px-4 sm:py-2">
            </div>

            <div class="mb-3 text-left relative">
                <label for="password" class="block text-xs text-orange-500 mb-1 transition-all duration-200 sm:text-sm">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-3 py-1.5 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 input-focus-effect text-sm sm:px-4 sm:py-2">
                <button type="button" id="togglePassword" class="absolute right-2 top-7 text-orange-500 hover:text-orange-600 transition-all duration-200 hover:scale-105 sm:right-3 sm:top-8">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-3 flex items-center text-left">
                <input type="checkbox" id="rememberMe" name="rememberMe" class="mr-1 h-3 w-3 text-orange-500 focus:ring-orange-400 border-gray-400 rounded sm:h-4 sm:w-4">
                <label for="rememberMe" class="text-xs text-orange-500 sm:text-sm">Remember me</label>
            </div>

            <div class="mb-3 text-left">
                <p class="text-xs text-orange-400">
                    Lupa sandi? 
                    <a href="https://wa.me/6282241992151?text=Hallo%20mas%20Wahyu%20saya%20lupa%20kata%20sandi" target="_blank" class="text-orange-500 hover:text-orange-300 underline font-semibold transition-all duration-200 hover:scale-105 inline-block">
                        Klik disini
                    </a>
                </p>
            </div>

            <button type="submit" id="loginButton"
                class="w-full bg-orange-500 text-white py-1.5 rounded-md font-bold text-base button-hover-effect ripple-effect flex items-center justify-center mb-2 sm:py-2 sm:text-lg">
                <span id="buttonText">LOGIN</span>
                <div class="loading-spinner ml-1 sm:ml-2"></div>
            </button>
        </form>

        <div id="errorPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-4 rounded-lg shadow-md text-center fade-in-scale">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p id="errorMessage" class="text-red-600 font-bold mb-3 text-sm sm:text-base">Authentication Failed!</p>
                    <button id="closePopup" class="bg-orange-500 text-white px-4 py-1 rounded-md hover:bg-orange-600 transition-all duration-200 button-hover-effect text-sm sm:px-6 sm:py-2">OK</button>
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
                particle.style.animationDuration = (Math.random() * 5 + 4) + 's';
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