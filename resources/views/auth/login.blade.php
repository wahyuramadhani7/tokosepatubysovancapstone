<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEBASTIAN SOVAN - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* You'll replace this with your own background image */
            background-image: url('./images/bglogin.png');
            background-size: cover;
            background-position: center;
        }
        
        .login-container {
            background-color: #222;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 20px;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            background-color: #1e3c5c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .logo span {
            color: #ff5722;
            font-weight: bold;
            font-size: 16px;
        }
        
        h1 {
            color: #ff5722;
            margin-bottom: 20px;
            font-size: 24px;
            letter-spacing: 1px;
        }
        
        .welcome-text {
            color: #ff5722;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ff5722;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 16px;
        }
        
        .btn-login {
            width: 100%;
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn-login:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo">
                <span>SEPATU BY SOVAN</span>
            </div>
        </div>
        <h1>LOGIN</h1>
        <p class="welcome-text">Welcome back! Please login to your account</p>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>
</body>
</html>