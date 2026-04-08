<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Web Kit ATMI</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo-ykbs-transparan.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    {{-- Development Notification Bubble --}}
    @if(app()->environment() === 'local')
        @if(!file_exists(public_path('hot')))
            <div class="dev-bubble warning" id="devNotification">
                <span class="dot"></span>
                NPM dev server tidak berjalan — jalankan <strong>npm run dev</strong>
                <button onclick="closeBubble()" aria-label="Tutup">×</button>
            </div>
        @else
            <div class="dev-bubble success" id="devNotification">
                <span class="dot"></span>
                Hot reload aktif
                <button onclick="closeBubble()" aria-label="Tutup">×</button>
            </div>
        @endif
    @endif

    {{-- Main Page Layout --}}
    <div class="page">
        {{-- Left Panel: Branding --}}
        <div class="panel-left">
            <div class="brand-row">
                <div class="brand-icon">
                    <img src="{{ asset('logo-ykbs-transparan.png') }}" alt="Logo YKBS">
                </div>
                <span class="brand-name">WK / ATMI</span>
            </div>

            <div class="panel-body">
                <h1 class="panel-title">
                    ATMI<br>
                    <strong>Web Kit</strong>
                </h1>
                <p class="panel-desc">
                    Platform manajemen terpercaya untuk mengelola pengguna, peran, dan izin akses dengan mudah.
                </p>
                <div class="status-pill">
                    <span class="status-dot"></span>
                    Sistem aktif &amp; aman
                </div>
            </div>

            <div class="panel-footer">© {{ date('Y') }} Yayasan YKBS</div>
        </div>

        {{-- Right Panel: Login Form --}}
        <div class="panel-right">
            <div class="form-heading">
                <h2 class="form-title">Selamat datang</h2>
                <p class="form-sub">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            {{-- Session Status Alert --}}
            @if (session('status'))
                <div class="alert-status">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email Field --}}
                <div class="field">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@example.com"
                    />
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="field">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="form-row">
                    <label class="check-label">
                        <input type="checkbox" name="remember" id="remember_me">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-forgot">Lupa password?</a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-login">Masuk</button>
            </form>

         
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        window.closeBubble = function () {
            const el = document.getElementById('devNotification');
            if (!el) return;
            el.classList.add('bubble-hide');
            setTimeout(() => el.remove(), 300);
        };

        @if(app()->environment() === 'local')
        fetch('http://localhost:5173/__vite_ping', { mode: 'no-cors' }).catch(() => {
            const el = document.getElementById('devNotification');
            if (el && el.classList.contains('success')) {
                el.className = 'dev-bubble warning';
                el.innerHTML = `
                    <span class="dot"></span>
                    NPM dev server tidak berjalan — jalankan <strong>npm run dev</strong>
                    <button onclick="closeBubble()" aria-label="Tutup">×</button>
                `;
            }
        });
        @endif
    </script>
</body>
</html>
