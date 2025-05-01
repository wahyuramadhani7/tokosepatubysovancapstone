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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4 text-left">
                <label for="username" class="block text-sm text-orange-500 mb-1">Username</label>
                <input type="text" id="username" name="email" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div class="mb-6 text-left">
                <label for="password" class="block text-sm text-orange-500 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded-md border border-gray-400 bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <button type="submit"
                class="w-full bg-orange-500 text-white py-2 rounded-md font-bold text-lg hover:bg-orange-600 transition">
                LOGIN
            </button>
        </form>
    </div>
</body>
</html>
