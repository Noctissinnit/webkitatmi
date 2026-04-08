<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web Kit ATMI</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo-ykbs-transparan.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <script type="module">
        function closeBubble() {
            const bubble = document.getElementById('devNotification');
            if (bubble) {
                bubble.classList.add('bubble-hide');
                setTimeout(() => bubble.remove(), 300);
            }
        }

        // Check if Vite dev server is running
        if ('{{ app()->environment() }}' === 'local') {
            fetch('http://localhost:5173/__vite_ping', { mode: 'no-cors' })
                .catch(() => {
                    // Dev server tidak running - tampilkan warning
                    const bubble = document.getElementById('devNotification');
                    if (bubble && bubble.classList.contains('success')) {
                        bubble.classList.remove('success');
                        bubble.classList.add('warning');
                        bubble.innerHTML = `
                            <div class="bubble-content">
                                <div class="bubble-icon">🚨</div>
                                <div class="bubble-text">
                                    <p class="bubble-title">NPM DEV SERVER TIDAK BERJALAN!</p>
                                    <p class="bubble-message">Silakan jalankan: <span class="bubble-code">npm run dev</span> di terminal sebelum development</p>
                                </div>
                                <button class="bubble-close" onclick="closeBubble()" aria-label="Close">×</button>
                            </div>
                        `;
                    }
                });
        }
    </script>

    <!-- NAV -->
    <nav>
        <a href="/" class="nav-logo">WK/<span>ATMI</span></a>
        @if (Route::has('login'))
            <div class="nav-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-solid">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-solid">Daftar</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    @if(app()->environment() === 'local')
    <!-- NPM DEV SERVER NOTIFICATION BUBBLE -->
    @if(!file_exists(public_path('hot')))
    <div class="dev-notification-bubble warning" id="devNotification">
        <div class="bubble-content">
            <div class="bubble-icon">🚨</div>
            <div class="bubble-text">
                <p class="bubble-title">NPM DEV SERVER TIDAK BERJALAN!</p>
                <p class="bubble-message">Silakan jalankan: <span class="bubble-code">npm run dev</span> di terminal sebelum development</p>
            </div>
            <button class="bubble-close" onclick="closeBubble()" aria-label="Close">×</button>
        </div>
    </div>
    @else
    <div class="dev-notification-bubble success" id="devNotification">
        <div class="bubble-content">
            <div class="bubble-icon">✅</div>
            <div class="bubble-text">
                <p class="bubble-title">NPM Dev Server Berjalan</p>
                <p class="bubble-message">Hot reload aktif — setiap perubahan CSS/JS akan ter-update otomatis</p>
            </div>
            <button class="bubble-close" onclick="closeBubble()" aria-label="Close">×</button>
        </div>
    </div>
    @endif
    @endif

    <!-- HERO -->
    <section class="hero">
        <div class="hero-main">
            <div class="hero-left">
                <div>
                    <div style="margin-bottom: 1.25rem;">
                        <img src="{{ asset('logo-ykbs-transparan.png') }}" alt="Logo Yayasan">
                    </div>
                    <p class="hero-eyebrow">Platform Manajemen — v1.0</p>
                    <h1 class="hero-title">
                        ATMI<br>
                        <em>WEB KIT</em>
                    </h1>
                </div>
                <div class="hero-cta">
                    <a href="{{ route('dashboard') }}" class="btn-primary">Get Started</a>
                    <a href="#fitur" class="btn-secondary">Pelajari →</a>
                </div>
            </div>

            <div class="hero-right">
                <p class="hero-desc">
                    Antarmuka terpadu untuk mengelola peran, pengguna, dan izin akses secara efisien.
                </p>
                <div class="hero-visual">
                    <div class="grid-lines"></div>
                    <!-- Card mock 1 -->
                    <div class="card-mock card-mock-1">
                        <div class="mock-label">Pengguna Aktif</div>
                        <div class="mock-bar filled"></div>
                        <div class="mock-bar short"></div>
                        <div class="mock-dot-row">
                            <div class="mock-dot active"></div>
                            <div class="mock-dot active"></div>
                            <div class="mock-dot"></div>
                        </div>
                    </div>
                    <!-- Card mock 2 -->
                    <div class="card-mock card-mock-2">
                        <div class="mock-label">Peran</div>
                        <div class="mock-bar medium"></div>
                        <div class="mock-bar" style="width:50%"></div>
                    </div>
                    <!-- Card mock 3 -->
                    <div class="card-mock card-mock-3">
                        <div class="mock-label">Izin Sistem</div>
                        <div class="mock-bar" style="width:80%"></div>
                        <div class="mock-bar short"></div>
                    </div>
                </div>
            </div>
        </div>

     
    </section>
</body>
</html>