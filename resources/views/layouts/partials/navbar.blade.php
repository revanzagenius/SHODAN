<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar with Logo</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 60px; /* to prevent content from being hidden under the navbar */
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgb(27, 27, 27); /* Navbar hitam */
            color: white;
            z-index: 1000;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: top 0.3s;
        }

        /* Logo Styles */
        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            padding: 5px 0;
        }

        /* Nav Links Styles */
        .nav-links {
            display: flex;
            align-items: center;
            margin-left: auto; /* Menggeser elemen ke kanan */
            gap: 10px; /* Jarak antar link */
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: background-color 0.3s;
        }

        /* Logo Styles */
        .navbar img {
            height: 80px; /* Tinggi logo diatur ke 40px */
            width: auto; /* Menjaga aspek rasio */
            margin-right: 20px; /* Memberi jarak antara logo dan menu */
        }

        /* Hover effect for links */
        .nav-links a:hover {
            background-color: red; /* Darker color on hover */
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: black; /* Dropdown dengan latar belakang hitam */
            min-width: 160px;
            z-index: 1;
        }

        .dropdown-content a {
            padding: 12px 20px;
            color: white; /* Warna teks putih untuk submenu */
        }

        /* Change color of submenu on hover */
        .dropdown-content a:hover {
            background-color: red; /* Warna merah saat hover pada submenu */
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links a {
                width: 100%;
                padding: 10px;
            }

            .dropdown-content {
                width: 100%;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <!-- Logo -->
        <img src="{{ asset('logoremovebg.png') }}" alt="Logo">

        <!-- Navigation Links -->
        <div class="nav-links">
            <div class="dropdown">
                <a href="#">Monitor</a>
                <div class="dropdown-content">
                    <a href="{{ route('dashboard.index') }}">Vulnerability Monitor</a>
                    <a href="{{ route('domains.index') }}">Domain Monitor</a>
                </div>
            </div>
            <a href="{{ route('ipscanner') }}">Vulnerability Scanner</a>
            <a href="{{ route('search.index') }}">OSINT Tools</a>
            <div class="dropdown">
                <a href="#">Feeds</a>
                <div class="dropdown-content">
                    <a href="{{ route('news.index') }}">News</a>
                    <a href="{{ route('malware.index') }}">Malware Trends</a>
                </div>
            </div>
            |
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="
                    background-color: red;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 5px;
                    cursor: pointer;">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
