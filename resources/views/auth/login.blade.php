<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEPATU BY SOVAN - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 40px rgba(249, 115, 22, 0.6); }
        }
        
        @keyframes slideInDown {
            0% {
                transform: translateY(-100px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes slideInUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeInScale {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 1;
            }
            50% { 
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0);
                opacity: 0.6;
            }
            100% {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        .glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        .slide-in-down {
            animation: slideInDown 0.8s ease-out;
        }
        
        .slide-in-up {
            animation: slideInUp 0.6s ease-out;
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }
        
        .fade-in-scale {
            animation: fadeInScale 0.5s ease-out;
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }
        
        .input-focus-effect {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .input-focus-effect:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3);
        }
        
        .button-hover-effect {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .button-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.4);
        }
        
        .button-hover-effect:active {
            transform: translateY(0);
        }
        
        .ripple-effect {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .shake-error {
            animation: shake 0.5s ease-in-out;
        }
        
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
            width: 4px;
            height: 4px;
            background: rgba(249, 115, 22, 0.3);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-cover bg-center min-h-screen flex items-center justify-center relative overflow-hidden" style="background-image: url('./images/bglogin.png')">
    <!-- Background Particles -->
    <div class="background-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 0.5s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 2.5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 0.8s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 1.8s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 2.8s;"></div>
    </div>

    <div class="bg-gray-900 w-full max-w-md p-8 rounded-lg shadow-lg text-center slide-in-down backdrop-blur-sm bg-opacity-95">
        <div class="mb-6">
            <div class="w-28 h-28 bg-blue-900 rounded-full flex items-center justify-center mx-auto float glow transition-all duration-500 hover:scale-110">
                <span class="text-orange-500 font-bold text-sm">SEPATU BY SOVAN</span>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-orange-500 mb-2 slide-in-up">LOGIN</h1>
        <p class="text-sm text-orange-500 mb-6 slide-in-up">Welcome back! Please login to your account</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="fade-in-scale">
            @csrf
            <div class="mb-4 text-left">
                <label for="username" class="block text-sm text-orange-500 mb-1 transition-all duration-300">Username</label>
                <input type="text" id="username" name="email" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 input-focus-effect">
            </div>

            <div class="mb-4 text-left relative">
                <label for="password" class="block text-sm text-orange-500 mb-1 transition-all duration-300">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 input-focus-effect">
                <button type="button" id="togglePassword" class="absolute right-3 top-9 text-orange-500 hover:text-orange-600 transition-all duration-300 hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-6 text-left">
                <p class="text-xs text-orange-400">
                    Lupa sandi? 
                    <a href="https://wa.me/6282241992151?text=Hallo%20mas%20Wahyu%20saya%20lupa%20kata%20sandi" target="_blank" class="text-orange-500 hover:text-orange-300 underline font-semibold transition-all duration-300 hover:scale-105 inline-block">
                        Klik disini
                    </a>
                </p>
            </div>

            <button type="submit" id="loginButton"
                class="w-full bg-orange-500 text-white py-2 rounded-md font-bold text-lg button-hover-effect ripple-effect flex items-center justify-center">
                <span id="buttonText">LOGIN</span>
                <div class="loading-spinner ml-2"></div>
            </button>
        </form>

        <div id="errorPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center fade-in-scale">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p id="errorMessage" class="text-red-600 font-bold mb-4">Password Salah!</p>
                    <button id="closePopup" class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600 transition-all duration-300 button-hover-effect">OK</button>
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
        const loadingSpinner = document.querySelector('.loading-spinner');

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

        // Add ripple effect to login button
        loginButton.addEventListener('click', createRipple);

        window.onload = function() {
            // Check for server-side errors (Laravel session errors)
            @if ($errors->has('email') || $errors->has('password'))
                errorMessage.textContent = 'Password Salah!';
                errorPopup.classList.remove('hidden');
                // Add shake animation to form
                document.querySelector('.bg-gray-900').classList.add('shake-error');
                setTimeout(() => {
                    document.querySelector('.bg-gray-900').classList.remove('shake-error');
                }, 500);
            @endif

            // Add floating animation to particles
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDuration = (Math.random() * 10 + 5) + 's';
            });
        };

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Add pulse animation
            togglePassword.style.animation = 'pulse 0.3s ease-in-out';
            setTimeout(() => {
                togglePassword.style.animation = '';
            }, 300);
            
            togglePassword.innerHTML = type === 'password' ?
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>` :
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5m0 0L21 21"></path>
                </svg>`;
        });

        loginForm.addEventListener('submit', (e) => {
            console.log('Form submitted');
            
            // Show loading animation
            buttonText.textContent = 'LOADING...';
            loadingSpinner.style.display = 'block';
            loginButton.disabled = true;
            loginButton.classList.add('opacity-75');
        });

        closePopup.addEventListener('click', () => {
            errorPopup.classList.add('hidden');
        });

        // Add input focus animations
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

        // Add click animation to close popup
        closePopup.addEventListener('click', createRipple);
    </script>
</body>
</html>