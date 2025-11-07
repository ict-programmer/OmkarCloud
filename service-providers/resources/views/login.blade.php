<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Monitoring App</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header .logo {
            font-size: 48px;
            color: #00ed64;
            margin-bottom: 15px;
            text-align: center;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }

        .login-header p {
            color: #666;
            font-size: 16px;
            margin: 10px 0 0 0;
        }

        .login-form {
            text-align: left;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00ed64;
            box-shadow: 0 0 0 3px rgba(0, 237, 100, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: #00ed64;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 237, 100, 0.3);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fee;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .success-message {
            background: #f0fff4;
            color: #38a169;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }



        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-database"></i>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to access Monitoring App</p>
        </div>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form class="login-form" id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span id="loginBtnText">Sign In</span>
                <span id="loginBtnLoading" class="loading" style="display: none;"></span>
            </button>
        </form>


    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnText = document.getElementById('loginBtnText');
            const loginBtnLoading = document.getElementById('loginBtnLoading');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            // Hide previous messages
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            // Show loading state
            loginBtn.disabled = true;
            loginBtnText.style.display = 'none';
            loginBtnLoading.style.display = 'inline-block';

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ username, password })
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    successMessage.textContent = 'Login successful! Redirecting...';
                    successMessage.style.display = 'block';
                    
                    // Store user data if needed
                    if (result.user) {
                        localStorage.setItem('user', JSON.stringify(result.user));
                    }
                    
                    // Redirect to manual page after a short delay
                    setTimeout(() => {
                        window.location.href = '/manual';
                    }, 1000);
                } else {
                    // Show error message
                    errorMessage.textContent = result.message || 'Login failed. Please try again.';
                    errorMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Login error:', error);
                errorMessage.textContent = 'An error occurred. Please try again.';
                errorMessage.style.display = 'block';
            } finally {
                // Reset button state
                loginBtn.disabled = false;
                loginBtnText.style.display = 'inline';
                loginBtnLoading.style.display = 'none';
            }
        });

        // Check if user is already authenticated
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('/check-auth');
                const result = await response.json();
                
                if (result.authenticated) {
                    // User is already logged in, redirect to manual
                    window.location.href = '/manual';
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                // Continue showing login form
            }
        });
    </script>
</body>
</html>
