<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        h1 {
            color: #333;
        }
        
        form {
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button[type="submit"]:hover {
            background-color: #555;
        }
        
        p {
            margin-top: 10px;
        }
        
        p a {
            color: #333;
            text-decoration: none;
        }
        
        p a:hover {
            text-decoration: underline;
        }
        .text-center{
            text-align:center;
        }
        .login-wrapper{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
    <h1>Login</h1>
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    </div>
    <p class="text-center">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
</body>
</html>
