<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Gipstore Portfolio' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Header Styles */
        .header {
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .header-content {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: auto;
        }
        .logo img {
            height: 25px;
        }
        .nav-center {
            display: flex;
            gap: 1.5rem;
        }
        .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        .nav-link {
            text-decoration: none;
            color: #333;
            font-size: 20px;
            font-weight: 600;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #6a41e4;
        }

        /* Main Content Styling */
        body {
            padding-top: 100px; 
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Services Page Styling */
        .services-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .services-page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .services-page-description {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 40px;
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .services-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-item {
            background-color: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .service-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .service-content {
            padding: 25px;
        }

        .service-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .service-description {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .service-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .service-package {
            font-size: 0.9rem;
            color: #6a41e4;
            font-weight: 600;
        }

        .service-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
        }

        .service-order-btn {
            display: block;
            width: 100%;
            background-color: #6a41e4;
            color: #fff;
            border: none;
            padding: 12px 0;
            margin-top: 20px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
        }

        .service-order-btn:hover {
            background-color: #5632c5;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .services-list {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            .services-page-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .services-list {
                grid-template-columns: 1fr;
            }
            .services-page-title {
                font-size: 1.8rem;
            }
            .services-page-description {
                font-size: 1rem;
            }
        }

        /* Social Media Footer Styling */
        .social-footer {
            background-color: #6a41e4;
            padding: 30px 0;
            text-align: center;
            color: #fff;
            margin-top: 50px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .social-icon i {
            font-size: 30px;
            background-color: #f8f8f8;
            border-radius: 50%;
            padding: 10px;
            color: #333;
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .social-icon i:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
            background-color: #ffdd57;
            color: #6a41e4;
        }

        .social-footer p {
            font-size: 14px;
            font-weight: 600;
            color: #ffdd57;
            margin: 0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Gipstore Logo">
            </div>
            <div class="nav-center">
                <a href="{{ route('client.dashboard') }}" class="nav-link">Home</a>
                <a href="{{ route('client.layanan') }}" class="nav-link">Layanan</a>
                <a href="{{ route('client.pesanan.index') }}" class="nav-link">Pesanan</a>
                <a href="{{ route('client.chat.index') }}" class="nav-link">Chat</a>
                {{-- <a href="{{ route('client.pesanan.index') }}" class="nav-link">Pesan</a> --}}
                {{-- <a href="{{ url('chatify') }}" class="nav-link">Chat</a> --}}
            </div>
            <div class="nav-links">
                <form action="{{ route('client.logout') }}" method="POST" style="margin: 0; padding: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer; font-size: 20px; font-weight: 600; color: #333;">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    @yield('content')

    <!-- Social Media Footer -->
    <footer class="social-footer">
        <div class="social-icons">
            <a href="https://www.instagram.com/gipstore.catalogue/" class="social-icon">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://wa.me/+6285746178059" class="social-icon">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://discord.gg/ZZatawHNWy" class="social-icon">
                <i class="bi bi-discord"></i>
            </a>
        </div>
        <p>&copy; Copyright by | gipstore 2024</p>
    </footer>
    
    @stack('scripts')
</body>
</html>