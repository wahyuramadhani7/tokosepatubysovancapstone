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

            <button type="button" id="fingerprintButton"
                class="w-full bg-blue-600 text-white py-1.5 rounded-md font-bold text-base button-hover-effect ripple-effect flex items-center justify-center mb-2 sm:py-2 sm:text-lg">
                <svg class="w-4 h-4 mr-1 sm:w-5 sm:h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm4 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2z"></path>
                </svg>
                <span id="fingerprintButtonText">LOGIN WITH FINGERPRINT</span>
                <div class="loading-spinner ml-1 sm:ml-2"></div>
            </button>

            <button type="button" id="registerFingerprintButton"
                class="w-full bg-green-600 text-white py-1.5 rounded-md font-bold text-base button-hover-effect ripple-effect flex items-center justify-center sm:py-2 sm:text-lg">
                <svg class="w-4 h-4 mr-1 sm:w-5 sm:h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm4 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2zm0 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 1.105.896 2 2 2s2-.895 2-2z"></path>
                </svg>
                <span id="registerFingerprintButtonText">REGISTER FINGERPRINT</span>
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

        <div id="successPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-4 rounded-lg shadow-md text-center fade-in-scale">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p id="successMessage" class="text-green-600 font-bold mb-3 text-sm sm:text-base">Fingerprint Registered Successfully!</p>
                    <button id="closeSuccessPopup" class="bg-orange-500 text-white px-4 py-1 rounded-md hover:bg-orange-600 transition-all duration-200 button-hover-effect text-sm sm:px-6 sm:py-2">OK</button>
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
        const fingerprintButton = document.getElementById('fingerprintButton');
        const fingerprintButtonText = document.getElementById('fingerprintButtonText');
        const registerFingerprintButton = document.getElementById('registerFingerprintButton');
        const registerFingerprintButtonText = document.getElementById('registerFingerprintButtonText');
        const successPopup = document.getElementById('successPopup');
        const successMessage = document.getElementById('successMessage');
        const closeSuccessPopup = document.getElementById('closeSuccessPopup');
        const rememberMe = document.getElementById('rememberMe');
        const loadingSpinners = document.querySelectorAll('.loading-spinner');

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

            // Check if WebAuthn is supported
            if (!window.PublicKeyCredential) {
                fingerprintButton.disabled = true;
                fingerprintButton.classList.add('opacity-50', 'cursor-not-allowed');
                fingerprintButtonText.textContent = 'FINGERPRINT NOT SUPPORTED';
                registerFingerprintButton.disabled = true;
                registerFingerprintButton.classList.add('opacity-50', 'cursor-not-allowed');
                registerFingerprintButtonText.textContent = 'FINGERPRINT NOT SUPPORTED';
            }
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
        [loginButton, fingerprintButton, registerFingerprintButton, closePopup, closeSuccessPopup].forEach(button => {
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
            loadingSpinners[0].style.display = 'block';
            loginButton.disabled = true;
            loginButton.classList.add('opacity-75');
        });

        function base64urlToArrayBuffer(base64url) {
            let base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
            const padding = base64.length % 4;
            if (padding) {
                base64 += '='.repeat(4 - padding);
            }
            const binaryString = window.atob(base64);
            const bytes = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        }

        function arrayBufferToBase64url(buffer) {
            let binary = '';
            const bytes = new Uint8Array(buffer);
            for (let i = 0; i < bytes.byteLength; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
        }

        async function registerFingerprint() {
            try {
                if (!usernameInput.value) {
                    errorMessage.textContent = 'Please enter a username before registering fingerprint!';
                    errorPopup.classList.remove('hidden');
                    document.querySelector('.bg-gray-900').classList.add('shake-error');
                    setTimeout(() => {
                        document.querySelector('.bg-gray-900').classList.remove('shake-error');
                    }, 400);
                    return;
                }

                registerFingerprintButtonText.textContent = 'REGISTERING...';
                loadingSpinners[2].style.display = 'block';
                registerFingerprintButton.disabled = true;
                registerFingerprintButton.classList.add('opacity-75');

                if (!window.PublicKeyCredential) {
                    throw new Error('WebAuthn is not supported in this browser');
                }

                const challenge = arrayBufferToBase64url(crypto.getRandomValues(new Uint8Array(32)));
                const publicKey = {
                    challenge: base64urlToArrayBuffer(challenge),
                    rp: { name: 'Sepatu by Sovan', id: window.location.hostname },
                    user: {
                        id: new TextEncoder().encode(usernameInput.value),
                        name: usernameInput.value,
                        displayName: usernameInput.value
                    },
                    pubKeyCredParams: [
                        { type: 'public-key', alg: -7 },
                        { type: 'public-key', alg: -257 }
                    ],
                    authenticatorSelection: {
                        authenticatorAttachment: 'platform',
                        userVerification: 'required'
                    },
                    timeout: 60000,
                    attestation: 'none'
                };

                const credential = await navigator.credentials.create({ publicKey });
                const credentialData = {
                    id: credential.id,
                    rawId: arrayBufferToBase64url(credential.rawId),
                    type: credential.type,
                    response: {
                        clientDataJSON: arrayBufferToBase64url(credential.response.clientDataJSON),
                        attestationObject: arrayBufferToBase64url(credential.response.attestationObject)
                    }
                };

                localStorage.setItem(`fingerprintCredentialId_${usernameInput.value}`, credential.id);
                successMessage.textContent = 'Fingerprint Registered Successfully!';
                successPopup.classList.remove('hidden');

            } catch (error) {
                console.error('Fingerprint registration failed:', error);
                errorMessage.textContent = 'Fingerprint Registration Failed: ' + error.message;
                errorPopup.classList.remove('hidden');
                document.querySelector('.bg-gray-900').classList.add('shake-error');
                setTimeout(() => {
                    document.querySelector('.bg-gray-900').classList.remove('shake-error');
                }, 400);
            } finally {
                registerFingerprintButtonText.textContent = 'REGISTER FINGERPRINT';
                loadingSpinners[2].style.display = 'none';
                registerFingerprintButton.disabled = false;
                registerFingerprintButton.classList.remove('opacity-75');
            }
        }

        async function authenticateWithFingerprint() {
            try {
                if (!usernameInput.value) {
                    errorMessage.textContent = 'Please enter a username before using fingerprint!';
                    errorPopup.classList.remove('hidden');
                    document.querySelector('.bg-gray-900').classList.add('shake-error');
                    setTimeout(() => {
                        document.querySelector('.bg-gray-900').classList.remove('shake-error');
                    }, 400);
                    return;
                }

                fingerprintButtonText.textContent = 'SCANNING...';
                loadingSpinners[1].style.display = 'block';
                fingerprintButton.disabled = true;
                fingerprintButton.classList.add('opacity-75');

                if (!window.PublicKeyCredential) {
                    throw new Error('WebAuthn is not supported in this browser');
                }

                const credentialId = localStorage.getItem(`fingerprintCredentialId_${usernameInput.value}`);
                if (!credentialId) {
                    throw new Error('No fingerprint registered for this user');
                }

                const challenge = arrayBufferToBase64url(crypto.getRandomValues(new Uint8Array(32)));
                const publicKey = {
                    challenge: base64urlToArrayBuffer(challenge),
                    allowCredentials: [{
                        id: base64urlToArrayBuffer(credentialId),
                        type: 'public-key',
                        transports: ['internal']
                    }],
                    timeout: 60000,
                    userVerification: 'required'
                };

                const credential = await navigator.credentials.get({ publicKey });
                const authData = {
                    id: credential.id,
                    rawId: arrayBufferToBase64url(credential.rawId),
                    type: credential.type,
                    response: {
                        authenticatorData: arrayBufferToBase64url(credential.response.authenticatorData),
                        clientDataJSON: arrayBufferToBase64url(credential.response.clientDataJSON),
                        signature: arrayBufferToBase64url(credential.response.signature),
                        userHandle: credential.response.userHandle ? arrayBufferToBase64url(credential.response.userHandle) : null
                    }
                };

                loginForm.submit();

            } catch (error) {
                console.error('Fingerprint authentication failed:', error);
                errorMessage.textContent = 'Fingerprint Authentication Failed: ' + error.message;
                errorPopup.classList.remove('hidden');
                document.querySelector('.bg-gray-900').classList.add('shake-error');
                setTimeout(() => {
                    document.querySelector('.bg-gray-900').classList.remove('shake-error');
                }, 400);
            } finally {
                fingerprintButtonText.textContent = 'LOGIN WITH FINGERPRINT';
                loadingSpinners[1].style.display = 'none';
                fingerprintButton.disabled = false;
                fingerprintButton.classList.remove('opacity-75');
            }
        }

        fingerprintButton.addEventListener('click', authenticateWithFingerprint);
        registerFingerprintButton.addEventListener('click', registerFingerprint);

        closePopup.addEventListener('click', () => {
            errorPopup.classList.add('hidden');
        });

        closeSuccessPopup.addEventListener('click', () => {
            successPopup.classList.add('hidden');
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