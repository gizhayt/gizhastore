<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Gipstore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
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

        /* Main Content Styles */
        .orders-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .orders-page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .new-order-btn {
            background-color: #6a41e4;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .new-order-btn:hover {
            background-color: #5632c5;
        }

        /* Table Styles */
        .orders-table-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .orders-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .orders-table tr:hover {
            background-color: #f8f9fa;
        }

        .orders-table tr:last-child td {
            border-bottom: none;
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 90px;
        }

        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-proses {
            background-color: #cff4fc;
            color: #055160;
        }

        .status-revisi {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-selesai {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #842029;
        }

        /* Action Button Styles */
        .action-btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .view-btn {
            background-color: #6a41e4;
            color: #fff;
        }

        .view-btn:hover {
            background-color: #5632c5;
        }
        
        .empty-orders {
            text-align: center;
            padding: 50px 0;
            color: #6c757d;
        }
        
        .empty-orders i {
            font-size: 50px;
            margin-bottom: 20px;
        }
        
        .empty-orders p {
            font-size: 1.2rem;
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

        /* Media Queries */
        @media (max-width: 768px) {
            .orders-table-container {
                overflow-x: auto;
            }
            
            .orders-table {
                min-width: 800px;
            }
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-success {
            background-color: #d4edda;
            color: #0f5132;
        }

        .status-expire,
        .status-cancel,
        .status-deny {
            background-color: #f8d7da;
            color: #842029;
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

    <main class="orders-page">
        <h1 class="orders-page-title">Daftar Pesanan</h1>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <div class="orders-header">
            <h2>Pesanan Anda</h2>
            <a href="{{ route('client.pesanan.create') }}" class="new-order-btn">
                <i class="bi bi-plus-circle"></i> Buat Pesanan Baru
            </a>
        </div>

        @if($pesanans->count() > 0)
            <div class="orders-table-container">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Layanan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Batas Waktu</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $pesanan)
                            <tr>
                                <td>{{ $pesanan->nomor_pesanan }}</td>
                                <td>{{ $pesanan->layanan->nama ?? '-' }}</td>
                                <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pesanan->batas_waktu)->format('d M Y') }}</td>
                                <td>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $pesanan->status }}">
                                        @switch($pesanan->status)
                                            @case('pending') Menunggu Pembayaran @break
                                            @case('diproses') Diproses @break
                                            @case('revisi') Revisi @break
                                            @case('selesai') Selesai @break
                                            @case('diterima') Diterima @break
                                            @case('dibatalkan') Dibatalkan @break
                                            @default {{ ucfirst($pesanan->status) }}
                                        @endswitch
                                    </span>
                                    @if($pesanan->status === 'pending' && $pesanan->pembayaran)
                                        <br>
                                        <small>
                                            <span class="status-badge status-{{ $pesanan->pembayaran->status_pembayaran }}">
                                                Pembayaran: {{ ucfirst($pesanan->pembayaran->status_pembayaran) }}
                                            </span>
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('client.pesanan.show', $pesanan->id) }}" class="action-btn view-btn">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-orders">
                <i class="bi bi-inbox"></i>
                <p>Anda belum memiliki pesanan. Klik "Buat Pesanan Baru" untuk memulai.</p>
            </div>
        @endif
    </main>

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