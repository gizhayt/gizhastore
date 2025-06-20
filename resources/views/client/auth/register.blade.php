<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Gipstore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; display: flex; align-items: stretch; justify-content: center; background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .container-full { width: 100%; height: 100%; display: flex; flex-direction: row; }
        .form-section { flex: 1; padding: 3rem; background-color: #ffffff; display: flex; flex-direction: column; justify-content: center; }
        .form-section .logo { position: absolute; top: 1rem; left: 3rem; }
        .illustration-section { flex: 1; background-color: #f7f7f7; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .illustration-section img { max-width: 100%; height: auto; }
        .btn-primary-custom { background-color: #333; color: #fff; width: 100%; font-size: 19px; border-radius: 30px; padding: 0.6rem; display: flex; align-items: center; justify-content: center; }
        .text-small { font-size: 16px; }
        .form-control { border-radius: 30px; padding: 0.75rem 1.5rem; }
        @media (max-width: 768px) { .container-full { flex-direction: column; } .illustration-section { display: none; } }
    </style>
</head>
<body>
    <div class="container-full">
        <div class="form-section">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Gipstore" style="height: 20px;">
            </div>
            <div class="text-center mb-4">
                <h2 class="fw-bold">Buat Akun Baru</h2>
                <p class="text-muted">Silakan isi data berikut untuk mendaftar</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('client.register.submit') }}" id="registerForm">
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control rounded-pill @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan Email Anda" required autocomplete="email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="Masukkan Kata Sandi Anda" required autocomplete="new-password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill" placeholder="Ulangi Kata Sandi Anda" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary-custom mb-3" id="submitBtn">
                    <span id="register-spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                    Daftar
                </button>

                <div class="text-center text-small mt-4">
                    Sudah punya akun? <a href="{{ route('client.login') }}" class="text-primary">Masuk</a>
                </div>
            </form>
        </div>

        <div class="illustration-section">
            <img src="{{ asset('images/illustration.png') }}" alt="Ilustrasi">
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var form = this;
            var submitButton = document.getElementById('submitBtn');
            var spinner = document.getElementById('register-spinner');
            if (form.submitted) { e.preventDefault(); return; }
            spinner.classList.remove('d-none');
            submitButton.disabled = true;
            form.submitted = true;
        });
    </script>
</body>
</html>
