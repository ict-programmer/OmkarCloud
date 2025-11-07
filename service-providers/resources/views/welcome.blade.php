<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Monitoring App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .welcome-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .welcome-text {
            color: #666;
            margin-bottom: 2rem;
        }
        .login-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1 class="welcome-title">Welcome to Monitoring App</h1>
        <p class="welcome-text">Please log in to access the app</p>
        <a href="/login" class="login-btn">Login</a>
    </div>
</body>
</html>
