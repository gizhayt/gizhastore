<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Revisi - Gipstore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            padding-top: 100px;
            background-color: #f8f9fa;
            overflow-x: hidden;
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 2rem;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        textarea, input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        textarea {
            height: 150px;
            resize: vertical;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #6a41e4;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #5632c5;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .alert-error, .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .revisi-info {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        /* Social Footer Styles */
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
</head>
<body>
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

    <div class="container">
        <h2>Pengajuan Revisi</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="revisi-info">
            <p><strong>Nomor Pesanan:</strong> {{ $pesanan->nomor_pesanan }}</p>
            <p><strong>Layanan:</strong> {{ $pesanan->layanan->nama }}</p>
            <p><strong>Revisi Tersisa:</strong> {{ $pesanan->revisi_tersisa }}</p>
        </div>

        <form action="{{ route('client.pesanan.submit-revision', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="deskripsi">Deskripsi Revisi <span style="color: red;">*</span></label>
                <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="file_pendukung">File Pendukung (opsional)</label>
                <input type="file" id="file_pendukung" name="file_pendukung">
                <small>Max: 10MB</small>
                @error('file_pendukung')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn">
                    <i class="bi bi-check-circle"></i> Kirim Permintaan Revisi
                </button>
                <a href="{{ route('client.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <footer class="social-footer">
        <div class="social-icons">
            <div class="social-icon"><i class="bi bi-facebook"></i></div>
            <div class="social-icon"><i class="bi bi-twitter"></i></div>
            <div class="social-icon"><i class="bi bi-instagram"></i></div>
        </div>
        <p>&copy; 2025 Gipstore, All Rights Reserved.</p>
    </footer>
</body>
</html>