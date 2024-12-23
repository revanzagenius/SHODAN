<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DASARATHA</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #111;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .container h1 {
            margin-bottom: 20px;
            color: #e53935;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 0, 0, 0.8);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            color: #fff;
            background-color: #e53935;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #b71c1c;
        }

        .links {
            margin-top: 15px;
        }

        .links a {
            text-decoration: none;
            color: #e53935;
            font-weight: 500;
            transition: 0.3s;
        }

        .links a:hover {
            color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="username">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan Email Anda" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password Anda" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="links">
        </div>
    </div>
</body>
</html>
