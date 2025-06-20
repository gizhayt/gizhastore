<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Gipstore</title>
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
            text-align: center;
        }
        .subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 2rem;
        }
        .payment-info {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: 10px;
            background-color: #f8f9fa;
            border: 1px solid #eee;
        }
        .payment-detail {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .payment-detail:last-child {
            border-bottom: none;
        }
        .payment-label {
            font-weight: 600;
            color: #555;
        }
        .payment-value {
            color: #333;
        }
        .total-row {
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #ddd;
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
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        .payment-methods {
            margin-bottom: 2rem;
        }
        .payment-method {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method:hover {
            border-color: #6a41e4;
            background-color: #f8f8ff;
        }
        .payment-method.selected {
            border-color: #6a41e4;
            background-color: #f0f0ff;
        }
        .payment-method-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-method-name {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .payment-method-icon {
            font-size: 1.5rem;
            color: #6a41e4;
        }
        .payment-timer {
            margin-top: 1rem;
            text-align: center;
            padding: 1rem;
            background-color: #fff3cd;
            border-radius: 8px;
            color: #856404;
        }
        .timer-value {
            font-weight: 700;
            font-size: 1.2rem;
            color: #dc3545;
        }
        .actions {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background-color: #6a41e4;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
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
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .payment-instructions {
            margin-top: 2rem;
            padding: 1.5rem;
            border-radius: 10px;
            background-color: #f8f9fa;
            border: 1px solid #eee;
        }
        .instruction-step {
            margin-bottom: 1rem;
            position: relative;
            padding-left: 30px;
        }
        .instruction-step:before {
            content: attr(data-step);
            position: absolute;
            left: 0;
            width: 24px;
            height: 24px;
            background-color: #6a41e4;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .instruction-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
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
    @if(isset($snapToken))
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
        <h2>Pembayaran Pesanan</h2>
        <p class="subtitle">Selesaikan pembayaran Anda untuk melanjutkan proses pesanan</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <div class="payment-info">
            <div class="payment-detail">
                <div class="payment-label">Nomor Pesanan</div>
                <div class="payment-value">{{ $pesanan->nomor_pesanan }}</div>
            </div>
            <div class="payment-detail">
                <div class="payment-label">Layanan</div>
                <div class="payment-value">{{ $pesanan->layanan->nama }}</div>
            </div>
            @if($pesanan->paketRevisi)
            <div class="payment-detail">
                <div class="payment-label">Paket Revisi</div>
                <div class="payment-value">{{ $pesanan->paketRevisi->nama }}</div>
            </div>
            @endif
            <div class="payment-detail">
                <div class="payment-label">Tanggal Pemesanan</div>
                <div class="payment-value">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="payment-detail total-row">
                <div class="payment-label">Total Pembayaran</div>
                <div class="payment-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        @if(isset($timeLeft) && $timeLeft > 0)
        <div class="payment-timer">
            <p>Sisa waktu pembayaran:</p>
            <p class="timer-value">
                <span id="hours">{{ floor($timeLeft / 3600) }}</span>:
                <span id="minutes">{{ floor(($timeLeft % 3600) / 60) }}</span>:
                <span id="seconds">{{ $timeLeft % 60 }}</span>
            </p>
        </div>
        @endif

        <div class="payment-methods">
            <h3>Pilih Metode Pembayaran</h3>
            
            <div class="payment-method" id="bank-transfer">
                <div class="payment-method-header">
                    <div class="payment-method-name">
                        <span class="payment-method-icon"><i class="bi bi-bank"></i></span>
                        <span>Transfer Bank</span>
                    </div>
                    <input type="radio" name="payment_method" value="bank_transfer">
                </div>
            </div>
            
            <div class="payment-method" id="credit-card">
                <div class="payment-method-header">
                    <div class="payment-method-name">
                        <span class="payment-method-icon"><i class="bi bi-credit-card"></i></span>
                        <span>Kartu Kredit/Debit</span>
                    </div>
                    <input type="radio" name="payment_method" value="credit_card">
                </div>
            </div>
        </div>

        @if(isset($bankInstructions))
        <div class="payment-instructions">
            <h3>Petunjuk Pembayaran</h3>
            
            @foreach($bankInstructions as $index => $instruction)
            <div class="instruction-step" data-step="{{ $index + 1 }}">
                <div class="instruction-title">{{ $instruction['title'] }}</div>
                <div class="instruction-content">{{ $instruction['content'] }}</div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="actions">
            <a href="{{ route('client.pesanan.show', $pesanan->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            @if(isset($snapToken))
            <button id="pay-button" class="btn btn-success">
                <i class="bi bi-credit-card-2-front"></i> Bayar Sekarang
            </button>
            @else
            <form action="{{ route('client.pesanan.processPayment', $pesanan->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-credit-card-2-front"></i> Bayar Sekarang
                </button>
            </form>
            @endif
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

    @if(isset($snapToken))
    <script type="text/javascript">
        // Select payment method
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                // Clear all selected
                paymentMethods.forEach(m => m.classList.remove('selected'));
                // Select current
                this.classList.add('selected');
                // Select radio
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        // Payment button
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route('client.pesanan.payment.finish', $pesanan->id) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('client.pesanan.payment.finish', $pesanan->id) }}';
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                    console.log(result);
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };

        // Countdown timer
        @if(isset($timeLeft) && $timeLeft > 0)
        let timeLeft = {{ $timeLeft }};
        
        function updateTimer() {
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert('Waktu pembayaran telah habis. Halaman akan dimuat ulang.');
                window.location.reload();
            }
            
            timeLeft--;
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        @endif
    </script>
    @endif
</body>
</html>