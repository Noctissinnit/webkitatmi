<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web Kit ATMI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Mono:wght@300;400&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #ffffff;
            --surface: #EDECE8;
            --border: #D8D6CF;
            --ink: #1A1917;
            --ink-muted: #7A7770;
            --accent: #2A2825;
            --highlight: #C8B89A;
            --mono: 'DM Mono', monospace;
            --sans: 'DM Sans', sans-serif;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #131211;
                --surface: #1E1C1A;
                --border: #2E2C29;
                --ink: #F0EEE8;
                --ink-muted: #7A7770;
                --accent: #E8E4DC;
                --highlight: #8C7A62;
            }
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            height: 60px;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
            backdrop-filter: blur(12px);
        }

        .nav-logo {
            font-family: var(--mono);
            font-size: 13px;
            letter-spacing: 0.08em;
            color: var(--ink-muted);
            text-decoration: none;
        }

        .nav-logo span { color: var(--ink); font-weight: 400; }

        .nav-actions { display: flex; gap: 0.75rem; align-items: center; }

        .btn-ghost {
            font-family: var(--sans);
            font-size: 13px;
            font-weight: 400;
            color: var(--ink-muted);
            text-decoration: none;
            padding: 0.4rem 0.75rem;
            border-radius: 4px;
            transition: color 0.15s;
        }
        .btn-ghost:hover { color: var(--ink); }

        .btn-solid {
            font-family: var(--sans);
            font-size: 13px;
            font-weight: 500;
            color: var(--bg);
            background: var(--ink);
            text-decoration: none;
            padding: 0.45rem 1rem;
            border-radius: 4px;
            transition: opacity 0.15s;
        }
        .btn-solid:hover { opacity: 0.8; }

        /* HERO */
        .hero {
            padding-top: 60px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero-main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1px solid var(--border);
        }

        .hero-left {
            padding: 5rem 2.5rem 4rem;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            animation: fadeUp 0.6s ease both;
        }

        .hero-eyebrow {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: clamp(2.8rem, 5vw, 4.5rem);
            font-weight: 300;
            letter-spacing: -0.03em;
            line-height: 1.05;
            color: var(--ink);
        }

        .hero-title em {
            font-style: italic;
            font-weight: 300;
            color: var(--ink-muted);
        }

        .hero-cta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 3rem;
        }

        .btn-primary {
            font-size: 13px;
            font-weight: 500;
            color: var(--bg);
            background: var(--ink);
            text-decoration: none;
            padding: 0.65rem 1.5rem;
            border-radius: 4px;
            transition: opacity 0.15s;
        }
        .btn-primary:hover { opacity: 0.75; }

        .btn-secondary {
            font-size: 13px;
            color: var(--ink-muted);
            text-decoration: none;
            transition: color 0.15s;
        }
        .btn-secondary:hover { color: var(--ink); }

        .hero-right {
            padding: 5rem 2.5rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            animation: fadeUp 0.6s 0.1s ease both;
        }

        .hero-desc {
            font-size: 15px;
            font-weight: 300;
            color: var(--ink-muted);
            line-height: 1.7;
            max-width: 340px;
            margin-bottom: 3rem;
        }

        /* GRID VISUAL */
        .hero-visual {
            position: relative;
            width: 100%;
            aspect-ratio: 1.2;
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            background: var(--surface);
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.5;
        }

        .card-mock {
            position: absolute;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            animation: float 4s ease-in-out infinite;
        }

        .card-mock-1 {
            top: 18%; left: 10%; width: 44%; padding: 1rem;
            animation-delay: 0s;
        }
        .card-mock-2 {
            top: 28%; right: 8%; width: 36%; padding: 0.75rem;
            animation-delay: 1.5s;
        }
        .card-mock-3 {
            bottom: 18%; left: 18%; width: 52%; padding: 0.75rem;
            animation-delay: 0.8s;
        }

        .mock-label {
            font-family: var(--mono);
            font-size: 9px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 0.5rem;
        }

        .mock-bar {
            height: 4px;
            border-radius: 2px;
            background: var(--border);
            margin-bottom: 0.35rem;
        }
        .mock-bar.filled { background: var(--ink); width: 60%; }
        .mock-bar.short { width: 35%; }
        .mock-bar.medium { width: 70%; }

        .mock-dot-row {
            display: flex;
            gap: 0.4rem;
            margin-top: 0.5rem;
        }
        .mock-dot {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--border);
        }
        .mock-dot.active { background: var(--ink); }

        /* STATS STRIP */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid var(--border);
        }

        .stat-item {
            padding: 2rem 2.5rem;
            border-right: 1px solid var(--border);
            animation: fadeUp 0.5s ease both;
        }
        .stat-item:last-child { border-right: none; }

        .stat-num {
            font-size: 2rem;
            font-weight: 300;
            letter-spacing: -0.04em;
            color: var(--ink);
            font-family: var(--mono);
        }

        .stat-label {
            font-size: 12px;
            color: var(--ink-muted);
            margin-top: 0.25rem;
            letter-spacing: 0.02em;
        }

        /* FEATURES */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid var(--border);
        }

        .feature-item {
            padding: 3rem 2.5rem;
            border-right: 1px solid var(--border);
            animation: fadeUp 0.5s ease both;
        }
        .feature-item:last-child { border-right: none; }

        .feature-index {
            font-family: var(--mono);
            font-size: 10px;
            color: var(--ink-muted);
            letter-spacing: 0.08em;
            margin-bottom: 1.5rem;
        }

        .feature-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .feature-desc {
            font-size: 13px;
            color: var(--ink-muted);
            line-height: 1.6;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        .feature-link {
            font-size: 12px;
            font-weight: 500;
            color: var(--ink);
            text-decoration: none;
            letter-spacing: 0.02em;
            border-bottom: 1px solid var(--border);
            padding-bottom: 2px;
            transition: border-color 0.15s;
        }
        .feature-link:hover { border-color: var(--ink); }

        /* FOOTER */
        footer {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            padding: 1.25rem 2.5rem;
            border-top: 1px solid var(--border);
        }

        .footer-copy {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--ink-muted);
            letter-spacing: 0.06em;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            font-size: 12px;
            color: var(--ink-muted);
            text-decoration: none;
            transition: color 0.15s;
        }
        .footer-links a:hover { color: var(--ink); }

        /* ANIMATIONS */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-6px); }
        }

        /* POP-UP BUBBLE */
        .dev-notification-bubble {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 360px;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            animation: slideIn 0.4s ease-out;
            font-family: var(--sans);
            max-width: calc(100% - 2rem);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(420px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .dev-notification-bubble.warning {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
        }

        .dev-notification-bubble.success {
            background-color: #dcfce7;
            border-left: 4px solid #22c55e;
        }

        .bubble-content {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .bubble-icon {
            font-size: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .bubble-text {
            flex: 1;
        }

        .bubble-title {
            font-weight: 700;
            font-size: 14px;
            margin: 0 0 0.5rem 0;
        }

        .dev-notification-bubble.warning .bubble-title {
            color: #7f1d1d;
        }

        .dev-notification-bubble.success .bubble-title {
            color: #166534;
        }

        .bubble-message {
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 0.25rem 0;
        }

        .dev-notification-bubble.warning .bubble-message {
            color: #991b1b;
        }

        .dev-notification-bubble.success .bubble-message {
            color: #166534;
        }

        .bubble-code {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 12px;
        }

        .bubble-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            margin-left: auto;
            flex-shrink: 0;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .bubble-close:hover {
            opacity: 1;
        }

        .dev-notification-bubble.warning .bubble-close {
            color: #7f1d1d;
        }

        .dev-notification-bubble.success .bubble-close {
            color: #166534;
        }

        .bubble-hide {
            animation: slideOut 0.3s ease-out forwards;
        }

        @keyframes slideOut {
            to {
                opacity: 0;
                transform: translateX(420px);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            .dev-notification-bubble {
                width: calc(100% - 2rem);
                bottom: 1rem;
                right: 1rem;
            }
        }
        @media (max-width: 768px) {
            nav { padding: 0 1.25rem; }

            .hero-main {
                grid-template-columns: 1fr;
            }
            .hero-left {
                padding: 3rem 1.25rem 2rem;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
            .hero-right { padding: 2rem 1.25rem; }
            .hero-visual { display: none; }
            .hero-desc { margin-bottom: 0; }

            .stats-strip { grid-template-columns: repeat(3, 1fr); }
            .stat-item { padding: 1.5rem 1rem; }
            .stat-num { font-size: 1.5rem; }

            .features { grid-template-columns: 1fr; }
            .feature-item {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 2rem 1.25rem;
            }
            .feature-item:last-child { border-bottom: none; }

            footer {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <script>
        function closeBubble() {
            const bubble = document.getElementById('devNotification');
            if (bubble) {
                bubble.classList.add('bubble-hide');
                setTimeout(() => {
                    bubble.remove();
                }, 300);
            }
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

        {{-- <!-- STATS -->
        <div class="stats-strip">
            <div class="stat-item">
                <div class="stat-num">100%</div>
                <div class="stat-label">Open Source</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">∞</div>
                <div class="stat-label">Skalabel</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">24/7</div>
                <div class="stat-label">Tersedia</div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features" id="fitur">
        <div class="feature-item">
            <div class="feature-index">01</div>
            <div class="feature-title">Manajemen Peran</div>
            <p class="feature-desc">Kontrol akses granular dengan sistem hierarki peran yang fleksibel.</p>
            <a href="{{ route('roles.index') }}" class="feature-link">Kelola Peran</a>
        </div>
        <div class="feature-item">
            <div class="feature-index">02</div>
            <div class="feature-title">Manajemen Pengguna</div>
            <p class="feature-desc">Tambah, edit, dan atur seluruh pengguna sistem dari satu tempat.</p>
            <a href="{{ route('users.index') }}" class="feature-link">Kelola Pengguna</a>
        </div>
        <div class="feature-item">
            <div class="feature-index">03</div>
            <div class="feature-title">Dashboard Terpadu</div>
            <p class="feature-desc">Pantau aktivitas dan statistik sistem secara real-time.</p>
            <a href="{{ route('dashboard') }}" class="feature-link">Buka Dashboard</a>
        </div>
    </section> --}}

 

</body>
</html>