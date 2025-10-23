<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Course System')</title>

    <!-- Bootstrap & Icons & Poppins -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #cfeeff;
            --accent: #0d6efd;
            --nav-bg: rgba(255,255,255,0.02);
            --card-bg: rgba(255,255,255,0.98);
            --muted: #6c757d;
        }

        html,body { height:100%; }
        body{
            margin:0;
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
            color: #021d36;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* Top navbar */
        .site-navbar {
            background: rgba(255,255,255,0.96);
            border-bottom: 1px solid rgba(3,40,70,0.06);
            box-shadow: 0 6px 18px rgba(3,40,70,0.04);
        }
        .nav-brand {
            font-weight:700;
            color: var(--accent);
            letter-spacing: -0.2px;
        }
        .nav-link { color: #06325a !important; font-weight:600; }
        .nav-link:hover { color: var(--accent) !important; }

        /* Decorative wheels (background) - subtle */
        .bg-wheel { position: fixed; border-radius:50%; pointer-events:none; filter: blur(14px); mix-blend-mode: lighten; z-index: 0; opacity: 0.08; }
        .bg-wheel.w1{ width:640px;height:640px; right:-220px; top:-140px; background: conic-gradient(from 110deg, rgba(13,110,253,0.36), rgba(3,169,244,0.22)); animation: slow-rotate 120s linear infinite; }
        .bg-wheel.w2{ width:420px;height:420px; left:-140px; bottom:-100px; background: conic-gradient(from 210deg, rgba(0,123,255,0.24), rgba(0,200,255,0.12)); animation: slow-rotate-rev 160s linear infinite; }
        @keyframes slow-rotate { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        @keyframes slow-rotate-rev { from{transform:rotate(0deg)} to{transform:rotate(-360deg)} }

        /* Wave layer for button ripples */
        .wave-layer { position: fixed; inset:0; pointer-events:none; z-index:1; }

        /* Main container spacing */
        main.container { position: relative; z-index: 2; padding-top: 1.5rem; padding-bottom: 3rem; }

        /* Flash messages consistent */
        .flash { border-radius: 10px; box-shadow: 0 8px 24px rgba(3,40,70,0.06); }

        /* Footer */
        footer.app-footer { background: transparent; border-top: 1px solid rgba(3,40,70,0.04); padding: 1rem 0; color: var(--muted); font-size: .92rem; }

        /* Small responsive tweaks */
        @media (max-width: 767.98px) {
            .nav-brand { font-size: 1rem; }
        }

        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .bg-wheel { animation: none !important; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- decorative wheels -->
    <div class="bg-wheel w1" aria-hidden="true"></div>
    <div class="bg-wheel w2" aria-hidden="true"></div>

    <!-- wave layer -->
    <div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg site-navbar">
        <div class="container">
            <a class="navbar-brand nav-brand" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap me-2"></i>FRS Portal
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="siteNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>

                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Manage Users</a>
                            </li>
                        @endif

                        @if(Auth::user()->role === 'lecturer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('courses.index') }}">Manage Courses</a>
                            </li>
                        @endif

                        @if(Auth::user()->role === 'student')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enrollments.available') }}">Available Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enrollments.my-courses') }}">My Courses</a>
                            </li>
                        @endif

                        <!-- profile dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name ?? Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.setup') }}">Edit Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="px-3 py-1">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-danger p-0">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        {{-- flash messages --}}
        @if(session('success'))
            <div class="alert alert-success flash">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger flash">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="app-footer">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} FRS Portal — Course Registration System. All rights reserved.</small>
        </div>
    </footer>

    <!-- minimal shared scripts (bootstrap + waves + wheel randomization) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Respect reduced motion
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Create wave ripple on hover/click for elements with data-wave
        document.querySelectorAll('[data-wave]').forEach(el => {
            if(reduceMotion) return;
            el.addEventListener('mouseenter', (e) => {
                const rect = e.currentTarget.getBoundingClientRect();
                createWaveAt(rect.left + rect.width/2, rect.top + rect.height/2);
            });
            el.addEventListener('click', (e) => {
                const rect = e.currentTarget.getBoundingClientRect();
                createWaveAt(rect.left + rect.width/2, rect.top + rect.height/2);
            });
        });

        function createWaveAt(pageX, pageY){
            const layer = document.getElementById('waveLayer');
            if(!layer) return;
            const wave = document.createElement('div');
            wave.className = 'wave';
            wave.style.position = 'absolute';
            wave.style.borderRadius = '50%';
            wave.style.width = '20px';
            wave.style.height = '20px';
            wave.style.background = 'rgba(13,110,253,0.12)';
            wave.style.left = (pageX / window.innerWidth) * 100 + '%';
            wave.style.top = (pageY / window.innerHeight) * 100 + '%';
            wave.style.transform = 'translate(-50%,-50%) scale(0.2)';
            wave.style.opacity = '0';
            wave.style.pointerEvents = 'none';
            wave.style.animation = 'wave-expand 2.2s cubic-bezier(.2,.8,.2,1) forwards';
            layer.appendChild(wave);
            setTimeout(()=> wave.remove(), 2400);
        }

        // tweak wheel animations randomly for subtle variation
        document.querySelectorAll('.bg-wheel').forEach((wheel, idx) => {
            if(reduceMotion) { wheel.style.animation = 'none'; return; }
            const base = 100 + Math.random()*80;
            wheel.style.animationDuration = base + 's';
            if(Math.random() < 0.5) wheel.style.animationDirection = 'reverse';
        });

        // keyboard accessibility helper for action-like elements
        document.querySelectorAll('button, a').forEach(btn => {
            btn.addEventListener('keydown', (e) => {
                if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active');
            });
            btn.addEventListener('keyup', (e) => {
                if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
