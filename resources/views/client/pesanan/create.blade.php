<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pesanan - Gipstore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        /* Form Styles */
        .services-page {
            max-width: 800px;
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

        .form-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #6a41e4;
            box-shadow: 0 0 0 3px rgba(106, 65, 228, 0.1);
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

        .select-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #fff;
        }

        .text-muted {
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Alert messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        /* Price Summary Section */
        .price-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .price-summary h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.2rem;
        }

        .price-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .price-total {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: 600;
            color: #6a41e4;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* File input styling */
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            background-color: #e9ecef;
            color: #495057;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: inline-block;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .file-input-label:hover {
            background-color: #dde2e6;
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .selected-file {
            margin-top: 8px;
            font-size: 0.9rem;
            color: #6a41e4;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Loading spinner */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Service info */
        .service-info, .revisi-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #e9f5ff;
            border-radius: 5px;
            font-size: 0.9rem;
            display: none;
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

    <main class="services-page">
        <h1 class="services-page-title">Buat Pesanan</h1>
    
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <div class="form-container">
            <form action="{{ route('client.pesanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    
                <div class="form-group">
                    <label for="layanan_id" class="form-label">Layanan <span style="color: red;">*</span></label>
                    <select name="layanan_id" id="layanan_id" class="select-control" required>
                        <option value="">Pilih Layanan</option>
                        @foreach ($layanan as $item)
                            <option value="{{ $item->id }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}">
                                {{ $item->nama }} - Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <div class="service-info" id="serviceInfo"></div>
                </div>
    
                <div class="form-group">
                    <label for="paket_revisi_id" class="form-label">Paket Revisi (opsional)</label>
                    <select name="paket_revisi_id" id="paket_revisi_id" class="select-control">
                        <option value="">Pilih Paket Revisi</option>
                        @foreach ($paketRevisi as $paket)
                            <option value="{{ $paket->id }}" data-harga="{{ $paket->harga }}" data-revisi="{{ $paket->jumlah_revisi }}">
                                {{ $paket->nama }} ({{ $paket->jumlah_revisi }}x revisi) - Rp {{ number_format($paket->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <div class="revisi-info" id="revisiInfo"></div>
                </div>
    
                <div class="form-group">
                    <label for="persyaratan" class="form-label">Persyaratan <span style="color: red;">*</span></label>
                    <textarea name="persyaratan" id="persyaratan" class="form-control" rows="4" required>{{ old('persyaratan') }}</textarea>
                    <span class="text-muted">Jelaskan detail persyaratan untuk pesanan Anda secara spesifik dan jelas.</span>
                </div>
    
                <div class="form-group">
                    <label for="file_pesanan" class="form-label">File Pendukung <span style="color: red;">*</span></label>
                    <input type="file" name="file_pesanan" id="file_pesanan" class="form-control" required>
                    <span class="text-muted">Ukuran maksimal 10MB. Format: PDF, DOCX, ZIP, dll.</span>
                </div>
    
                <div class="form-group">
                    <label for="batas_waktu" class="form-label">Batas Waktu <span style="color: red;">*</span></label>
                    <input type="date" name="batas_waktu" id="batas_waktu" class="form-control" required min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                    <span class="text-muted">Pilih tanggal minimal besok sebagai batas waktu pengerjaan.</span>
                </div>
    
                <div class="price-summary">
                    <h3>Rincian Harga</h3>
                    <div class="price-detail">
                        <span>Layanan:</span>
                        <span id="layananPrice">Rp 0</span>
                    </div>
                    <div class="price-detail">
                        <span>Paket Revisi:</span>
                        <span id="revisiPrice">Rp 0</span>
                    </div>
                    <div class="price-total">
                        <span>Total:</span>
                        <span id="totalPrice">Rp 0</span>
                    </div>
                </div>
    
                <button type="submit" class="service-order-btn">
                    <div class="btn-content">
                        <span>Buat Pesanan</span>
                    </div>
                </button>
            </form>
        </div>
    </main>
    
    <footer class="social-footer">
        <div class="social-icons">
            <div class="social-icon"><i class="bi bi-facebook"></i></div>
            <div class="social-icon"><i class="bi bi-twitter"></i></div>
            <div class="social-icon"><i class="bi bi-instagram"></i></div>
        </div>
        <p>&copy; 2025 Gipstore, All Rights Reserved.</p>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const layananSelect = document.getElementById('layanan_id');
            const revisiSelect = document.getElementById('paket_revisi_id');
            const layananPrice = document.getElementById('layananPrice');
            const revisiPrice = document.getElementById('revisiPrice');
            const totalPrice = document.getElementById('totalPrice');
            const serviceInfo = document.getElementById('serviceInfo');
            const revisiInfo = document.getElementById('revisiInfo');
    
            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }
    
            function updatePriceSummary() {
                let layananHarga = parseInt(layananSelect.selectedOptions[0]?.dataset.harga || 0);
                let revisiHarga = parseInt(revisiSelect.selectedOptions[0]?.dataset.harga || 0);
    
                layananPrice.textContent = formatRupiah(layananHarga);
                revisiPrice.textContent = formatRupiah(revisiHarga);
                totalPrice.textContent = formatRupiah(layananHarga + revisiHarga);
    
                serviceInfo.textContent = layananSelect.selectedOptions[0]?.dataset.deskripsi || '';
                serviceInfo.style.display = layananSelect.value ? 'block' : 'none';
    
                revisiInfo.textContent = revisiSelect.selectedOptions[0]?.dataset.revisi
                    ? `Paket ini memberikan ${revisiSelect.selectedOptions[0].dataset.revisi}x revisi.`
                    : '';
                revisiInfo.style.display = revisiSelect.value ? 'block' : 'none';
            }
    
            layananSelect.addEventListener('change', updatePriceSummary);
            revisiSelect.addEventListener('change', updatePriceSummary);
    
            updatePriceSummary();
        });
    </script>
    </body>
    </html>
    