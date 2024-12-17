<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASARATHA | OSINT Platform</title>
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
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background-color: #111;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 10;
            font-size: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #e53935;
        }

        .logo img {
            height: 100px;
            width: auto;
        }

        .nav-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-grow: 1;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            margin-right: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 20px;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #e53935;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        .nav-buttons a {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 18px;
            font-weight: 500;
            color: #fff;
            transition: 0.3s;
        }

        .login {
            background-color: #e53935;
            border: 1px solid #e53935;
        }

        .login:hover {
            background-color: #b71c1c;
        }

        .signup {
            background-color: transparent;
            border: 1px solid #e53935;
            color: #e53935;
        }

        .signup:hover {
            background-color: #e53935;
            color: #fff;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: url('https://enablingdevices.com/wp-content/uploads/2019/04/TwoMenLookingatcomputer.jpg') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            color: #fff;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero h1 {
            font-size: 80px;
            font-weight: 700;
            color: #e53935;
            z-index: 2;
        }

        .hero p {
            font-size: 24px;
            margin-top: 20px;
            color: #fff;
            z-index: 2;
        }

        /* Testimoni */
        .testimoni {
            padding: 50px 20px;
            text-align: center;
            background-color: #111;
        }

        .testimoni h2 {
            font-size: 36px;
            color: #e53935;
            margin-bottom: 20px;
        }

        .testimoni .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .testimoni .card {
            background: #222;
            padding: 20px;
            border-radius: 8px;
            max-width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .testimoni .card p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .testimoni .card h4 {
            font-size: 18px;
            font-weight: bold;
            color: #e53935;
        }

        /* Footer */
        footer {
            text-align: center;
            background-color: #000;
            color: #fff;
            padding: 15px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo">
            <img src="{{ asset('simple.png') }}" alt="Logo">
        </div>
        <div class="nav-container">
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#testimoni">Testimoni</a>
                <a href="#kontak">Kontak</a>
            </div>
            <div class="nav-buttons">
                <a href="{{ route('login.index') }}" class="login">Login</a>
                <a href="#" class="signup">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
            <h1>DASARATHA</h1>
            <p>Open Source Intelligence Platform</p>
    </section>

    <!-- Testimoni Section -->
    <section id="testimoni" class="testimoni">
        <h2>Testimoni Pengguna</h2>
        <div class="card-container">
            <div class="card">
                <p>"Platform ini sangat membantu investigasi OSINT saya. Hasilnya akurat dan cepat!"</p>
                <h4>- Budi Santoso</h4>
            </div>
            <div class="card">
                <p>"Fitur-fitur lengkap dan antarmuka yang mudah digunakan. Sangat direkomendasikan!"</p>
                <h4>- Siti Aminah</h4>
            </div>
            <div class="card">
                <p>"DASARATHA benar-benar mengubah cara kami menganalisis data open source."</p>
                <h4>- Rudi Hartono</h4>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2024 DASARATHA. Semua Hak Dilindungi.
    </footer>
</body>
</html>
