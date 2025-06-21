<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gipstore Portfolio</title>
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
            padding-top: 100px; /* Ensure content does not hide behind header */
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        main {
            display: flex;
            justify-content: center;
            padding: 0; /* Remove padding for full width */
            min-height: 70vh; /* Minimum height for main section */
        }

        /* Banner Section */
        .banner {
            width: 100%;
            height: 100vh; /* Full height of the viewport */
            background-image: url('{{ asset('images/banner.png') }}'); /* Replace with your banner path */
            background-size: cover; /* Ensure the image covers the entire area */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent repeating the image */
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            position: relative;
            padding: 0 20px; /* Add horizontal padding if needed */
        }

        .banner h1 {
            font-size: 3rem; /* Large size for headings */
            font-weight: 700;
            margin: 0 0 10px; /* No margin on top */
            z-index: 1; /* Ensure text is above any background */
        }

        .banner p {
            font-size: 1.5rem; /* Adjust for readability */
            font-weight: 400;
            margin-top: 10px; /* Space between heading and paragraph */
            z-index: 1; /* Ensure text is above any background */
        }

        /* Footer Styles */
        footer {
            background-color: #ffdd57;
            padding: 25px 0; /* Padding for the footer */
            text-align: center;
            width: 100%;
        }

        footer .service-tags span {
            color: #333;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 20px;
            display: inline-block;
        }

        footer .service-tags span:before {
            content: '★';
            color: #333;
            margin-right: 5px;
        }

        /* Services Section Styling */
        .services-section {
            background-color: #6a41e4;
            padding: 50px 0;
            text-align: center;
            color: #fff;
        }

        .services-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .services-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background-color: #5c35c2;
            border-radius: 10px;
            padding: 30px;
            width: 250px;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-header {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .service-card p {
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .service-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffdd57;
        }

        /* Projects Section Styling */
        .projects-section {
            padding: 50px 0;
            text-align: center;
            background-color: #fff;
        }

        .projects-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }

        .projects-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .project-card {
            background-color: #f8f8f8;
            border-radius: 15px;
            padding: 20px;
            width: 250px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .project-card:hover {
            transform: translateY(-10px);
        }

        .project-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .project-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .project-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #6a41e4;
        }

        /* Social Media Footer Styling */
        .social-footer {
            background-color: #6a41e4;
            padding: 30px 0;
            text-align: center;
            color: #fff;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            .nav-center {
                display: none; /* Hide navigation links on smaller screens */
            }
            .banner h1 {
                font-size: 2.5rem; /* Adjust for smaller screens */
            }
            .banner p {
                font-size: 1.25rem; /* Adjust for smaller screens */
            }
            footer {
                margin-top: 10px;
            }
            .services-container {
                flex-direction: column;
                align-items: center;
            }
            .projects-container {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            .nav-links {
                gap: 1rem;
                font-size: 16px;
            }
            .banner h1 {
                font-size: 1.8rem; /* Further reduce for very small screens */
            }
            .banner p {
                font-size: 1rem; /* Further reduce for very small screens */
            }
            footer {
                margin-top: 5px;
            }
        }

        /* No data message styling */
        .no-data {
            color: #666;
            font-style: italic;
            font-size: 1.1rem;
            padding: 40px;
        }
    </style>
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
            </div>
            <div class="nav-links">
                <form action="{{ route('client.logout') }}" method="POST" style="margin: 0; padding: 0;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer; font-size: 20px; font-weight: 600; color: #333;">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Banner Section -->
    <main>
        <div class="banner">
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="service-tags">
            @if($layanan->count() > 0)
                @foreach($layanan->take(4) as $service)
                    <span>{{ $service->nama }}</span>
                @endforeach
            @else
                <span>Streaming Pack</span>
                <span>Chibi Art</span>
                <span>Vector Art</span>
                <span>Logo Design</span>
            @endif
        </div>
    </footer>

    <!-- Services Section -->
    <section class="services-section">
        <h2 class="services-title">My Services</h2>
        <div class="services-container">
            @if($paketRevisi->count() > 0)
                @foreach($paketRevisi as $paket)
                    <div class="service-card">
                        <div class="service-header">{{ $paket->nama }}</div>
                        <p>{{ $paket->deskripsi ?? 'Jumlah Revisi: ' . $paket->jumlah_revisi }}</p>
                        <div class="service-price">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            @else
                <div class="no-data">
                    Tidak ada paket revisi yang tersedia saat ini.
                </div>
            @endif
        </div>
    </section>

    <!-- Projects Section -->
    <section class="projects-section">
        <h2 class="projects-title">My Latest Projects</h2>
        <div class="projects-container">
            @if($layanan->count() > 0)
                @foreach($layanan as $project)
                    <div class="project-card">
                        @if($project->gambar)
                            <img src="{{ asset('storage/' . $project->gambar) }}" alt="{{ $project->nama }}">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $project->nama }}">
                        @endif
                        <h3 class="project-title">{{ $project->nama }}</h3>
                        <div class="project-price">Rp {{ number_format($project->harga, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            @else
                <div class="no-data">
                    Tidak ada layanan yang tersedia saat ini.
                </div>
            @endif
        </div>
    </section>

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
</body>
</html>