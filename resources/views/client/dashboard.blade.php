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
    border-radius: 10px;
    margin-bottom: 15px;
}

.project-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

/* Responsive Styles for Projects Section */
@media (max-width: 768px) {
    .projects-container {
        flex-direction: column;
        align-items: center;
    }
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

    <!-- Banner Section -->
    <main>
        <div class="banner">
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="service-tags">
            <span>Streaming Pack</span>
            <span>Chibi Art</span>
            <span>Vector Art</span>
            <span>Logo Design</span>
        </div>
    </footer>

    <!-- Services Section -->
    <section class="services-section">
        <h2 class="services-title">My Services</h2>
        <div class="services-container">
            <div class="service-card">
                <div class="service-header">Basic Design</div>
                <p>Get 1 Revision/Image Harga Tetap</p>
            </div>
            <div class="service-card">
                <div class="service-header">Standard Design</div>
                <p>Get 3 Revisions/Image dan Blue Harga 35K</p>
            </div>
            <div class="service-card">
                <div class="service-header">Premium Design</div>
                <p>Get 5 Revisions/Image dan Mockup Harga 75K</p>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
<section class="projects-section">
    <h2 class="projects-title">My Latest Projects</h2>
    <div class="projects-container">
        <div class="project-card">
            <img src="{{ asset('storage/images/streaming-pack.png') }}" alt="Streaming Pack">
            <h3 class="project-title">Streaming Pack</h3>
        </div>
        <div class="project-card">
            <img src="{{ asset('storage/images/chibi-art.png') }}" alt="Chibi Art Illustration">
            <h3 class="project-title">Chibi Art Illustration</h3>
        </div>
        <div class="project-card">
            <img src="{{ asset('storage/images/vector-art.png') }}" alt="Vector Art Illustration">
            <h3 class="project-title">Vector Art Illustration</h3>
        </div>
        <div class="project-card">
            <img src="{{ asset('storage/images/logo-design.png') }}" alt="Logo Design">
            <h3 class="project-title">Logo Design</h3>
        </div>
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
