<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Gipstore</title>
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
        .detail-row {
            margin-bottom: 1rem;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            margin-top: 0.25rem;
        }
        .actions {
            margin-top: 2rem;
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
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .download-btn {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            min-width: 90px;
            text-align: center;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-diproses {
            background-color: #cff4fc;
            color: #055160;
        }
        .status-selesai {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-ditolak, .status-dibatalkan {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-revisi {
            background-color: #fff3cd;
            color: #856404;
        }
        .revisi-section {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .revisi-item {
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .payment-status {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .timer {
            font-weight: 600;
            color: #dc3545;
        }
        .mt-4 {
            margin-top: 1.5rem;
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
    @if(isset($pembayaran) && $pembayaran->snap_token && $paymentStatus === 'pending')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
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
        <h2>Detail Pesanan</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="detail-row">
            <div class="label">Nomor Pesanan:</div>
            <div class="value">{{ $pesanan->nomor_pesanan }}</div>
        </div>
        <div class="detail-row">
            <div class="label">Layanan:</div>
            <div class="value">{{ $pesanan->layanan->nama }}</div>
        </div>
        @if($pesanan->paketRevisi)
        <div class="detail-row">
            <div class="label">Paket Revisi:</div>
            <div class="value">{{ $pesanan->paketRevisi->nama }} ({{ $pesanan->revisi_tersisa }} revisi tersisa)</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="label">Tanggal Dipesan:</div>
            <div class="value">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
        </div>
        <div class="detail-row">
            <div class="label">Batas Waktu:</div>
            <div class="value">
                {{ \Carbon\Carbon::parse($pesanan->batas_waktu)->format('d M Y') }}
                @if($remainingDays > 0)
                    <span class="timer">({{ floor($remainingDays) }} hari lagi)</span>
                @elseif($remainingDays == 0)
                    <span class="timer">(Hari ini)</span>
                @else
                    <span class="timer">(Telah lewat {{ floor(abs($remainingDays)) }} hari)</span>
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="label">Harga Total:</div>
            <div class="value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
        </div>
        <div class="detail-row">
            <div class="label">Status:</div>
            <div class="value">
                <span class="status-badge status-{{ $pesanan->status }}">
                    @switch($pesanan->status)
                        @case('pending') Menunggu Pembayaran @break
                        @case('diproses') Diproses @break
                        @case('revisi') Revisi @break
                        @case('selesai') Selesai @break
                        @case('diterima') Diterima @break
                        @case('dibatalkan') Dibatalkan @break
                        @default {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                    @endswitch
                </span>
            </div>
        </div>

        @if($paymentStatus !== 'success' && $pesanan->status === 'pending')
            <div class="payment-status">
                <div class="label">Status Pembayaran:</div>
                <div class="value">
                    <span class="status-badge status-{{ $paymentStatus }}">
                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                    </span>
                </div>

                @if($paymentStatus === 'pending' && isset($pembayaran))
                    <div class="actions" style="margin-top: 1rem;">
                        <a href="{{ route('client.pesanan.processPayment', $pesanan->id) }}" class="btn">
                            <i class="bi bi-credit-card"></i> Bayar Sekarang
                        </a>

                        @if($pembayaran->snap_token)
                            <button id="pay-button" class="btn">
                                <i class="bi bi-credit-card-2-front"></i> Bayar dengan Midtrans
                            </button>
                        @endif

                        <button onclick="checkPaymentStatus()" class="btn">
                            <i class="bi bi-arrow-clockwise"></i> Cek Status Sekarang
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <div class="detail-row">
            <div class="label">Persyaratan:</div>
            <div class="value">{{ $pesanan->persyaratan }}</div>
        </div>

        @if($pesanan->file_pesanan)
            <div class="detail-row">
                <div class="label">File Bahan:</div>
                <div class="value">
                    <a href="{{ url('client/pesanan/' . $pesanan->id . '/download') }}" class="btn download-btn">
                        <i class="bi bi-download"></i> Unduh File
                    </a>
                </div>
            </div>
        @endif

        @if($pesanan->hasil_pesanan)
            <div class="detail-row">
                <div class="label">Hasil Pesanan:</div>
                <div class="value">
                    <a href="{{ route('client.pesanan.downloadHasil', $pesanan->id) }}" class="btn download-btn">
                        <i class="bi bi-download"></i> Unduh Hasil
                    </a>
                </div>
            </div>
        @endif

        @if($pesanan->status === 'diproses' && $pesanan->hasil_pesanan && $pesanan->status_revisi !== 'menunggu')
        <div class="mt-4">
                <form action="{{ route('client.pesanan.complete', $pesanan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyelesaikan pesanan ini? Setelah diselesaikan, Anda tidak dapat mengajukan revisi lagi.');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Selesaikan Pesanan
                    </button>
                </form>
            </div>
        @endif

        @if($canRequestRevision)
        <div class="actions">
            <a href="{{ route('client.pesanan.revision-form', $pesanan->id) }}" class="btn">
                <i class="bi bi-arrow-repeat"></i> Ajukan Revisi
            </a>
        </div>
        @endif

        @if(count($revisi) > 0)
            <div class="revisi-section">
                <h3>Riwayat Revisi</h3>
                @foreach($revisi as $item)
                    <div class="revisi-item">
                        <div class="detail-row">
                            <div class="label">Tanggal:</div>
                            <div class="value">{{ $item->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="label">Status:</div>
                            <div class="value">{{ ucfirst($item->status) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="label">Deskripsi:</div>
                            <div class="value">{{ $item->deskripsi }}</div>
                        </div>
                        @if($item->file_pendukung && is_array($item->file_pendukung))
                        <div class="detail-row">
                            <div class="label">File Pendukung:</div>
                            <div class="value">
                                @foreach($item->file_pendukung as $file)
                                    <a href="{{ asset('storage/' . $file) }}" class="btn download-btn" target="_blank">
                                        <i class="bi bi-download"></i> Unduh File
                                    </a><br>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <!-- Tambahkan button download hasil revisi di sini -->
                        @if($item->hasilRevisi && $item->hasilRevisi->count() > 0)
                        <div class="detail-row">
                            <div class="label">Hasil Revisi:</div>
                            <div class="value">
                                <a href="{{ route('client.pesanan.downloadHasilRevisi', $item->id) }}" class="btn download-btn">
                                    <i class="bi bi-download"></i> Unduh Hasil Revisi
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="actions" style="margin-top: 2rem;">
            <a href="{{ route('client.pesanan.index') }}" class="btn">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    <footer class="social-footer">
        <div class="social-icons">
            <div class="social-icon"><i class="bi bi-facebook"></i></div>
            <div class="social-icon"><i class="bi bi-twitter"></i></div>
            <div class="social-icon"><i class="bi bi-instagram"></i></div>
        </div>
        <p>&copy; 2025 Gipstore, All Rights Reserved.</p>
    </footer>

    @if(isset($pembayaran) && $pembayaran->snap_token && $paymentStatus === 'pending')
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $pembayaran->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = '{{ route('client.pesanan.payment.finish', $pesanan->id) }}';
                },
                onPending: function (result) {
                    window.location.href = '{{ route('client.pesanan.payment.finish', $pesanan->id) }}';
                },
                onError: function (result) {
                    alert('Pembayaran gagal!');
                    console.log(result);
                },
                onClose: function () {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };

        function checkPaymentStatus() {
            fetch('{{ route('client.pesanan.checkPaymentStatus', $pesanan->id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Gagal cek status pembayaran:', error));
        }

        setTimeout(function () {
            checkPaymentStatus();
            setInterval(checkPaymentStatus, 10000);
        }, 5000);
    </script>
    @endif
</body>
</html>