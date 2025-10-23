<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FRS Portal - Course Registration System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --secondary-blue: #0b63d4;
            --light-blue: #dff6ff;
            --bg-blue: #cfeeff;
            --text-dark: #011627;
            --text-muted: #5d6b78;
            --card-bg: rgba(255,255,255,0.98);
            --card-shadow: 0 8px 28px rgba(3,40,70,0.06);
            --border-color: #e6eef7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(180deg, var(--bg-blue) 0%, #eaf9ff 100%);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        .header {
            background: transparent;
            border-bottom: 1px solid rgba(11,37,69,0.04);
            padding: 1rem 0;
            position: relative;
            z-index: 5;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 6px 18px rgba(3,40,70,0.08);
        }

        .logo-text h1 {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
        }

        .logo-text p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* decorative background wheels */
        .bg-wheel {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(14px);
            mix-blend-mode: lighten;
            z-index: 0;
            opacity: 0.08;
        }
        .bg-wheel.w1 {
            width: 640px;
            height: 640px;
            right: -220px;
            top: -140px;
            background: conic-gradient(from 110deg, rgba(13,110,253,0.36), rgba(3,169,244,0.22));
            animation: slow-rotate 120s linear infinite;
        }
        .bg-wheel.w2 {
            width: 420px;
            height: 420px;
            left: -140px;
            bottom: -100px;
            background: conic-gradient(from 210deg, rgba(0,123,255,0.24), rgba(0,200,255,0.12));
            animation: slow-rotate-rev 160s linear infinite;
        }
        @keyframes slow-rotate { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        @keyframes slow-rotate-rev { from{transform:rotate(0deg)} to{transform:rotate(-360deg)} }

        .main-container {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            padding: 2rem 0;
            position: relative;
            z-index: 2;
        }

        .welcome-card {
            background: var(--card-bg);
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            max-width: 1100px;
            margin: 0 auto;
            border: 1px solid var(--border-color);
        }

        .welcome-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.6px;
        }

        .welcome-subtitle {
            font-size: 1.05rem;
            opacity: 0.95;
            margin-bottom: 1.75rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        /* prominent login CTA with subtle wave ripple hook */
        .login-btn {
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.18);
            color: white;
            padding: 12px 44px;
            font-size: 1.05rem;
            font-weight: 600;
            border-radius: 28px;
            text-decoration: none;
            transition: all 0.28s cubic-bezier(.2,.9,.2,1);
            display: inline-flex;
            gap: .6rem;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 26px rgba(3,40,70,0.12);
        }

        .login-btn:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-3px);
        }

        .features-grid {
            padding: 3rem 2rem;
            background: linear-gradient(180deg, rgba(250,251,255,0.99), rgba(246,250,255,0.99));
        }

        .feature-item {
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .feature-icon {
            width: 84px;
            height: 84px;
            background: var(--light-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.9rem;
            color: var(--primary-blue);
            box-shadow: 0 8px 20px rgba(3,40,70,0.04);
        }

        .feature-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .feature-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .info-section {
            background: var(--light-blue);
            padding: 2rem;
            text-align: center;
        }

        .info-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .info-text {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .footer {
            background: transparent;
            border-top: 1px solid rgba(11,37,69,0.04);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.92rem;
        }

        /* responsive adjustments */
        @media (max-width: 992px) {
            .welcome-title { font-size: 2.2rem; }
            .welcome-subtitle { font-size: 1rem; padding: 0 0.5rem; }
            .feature-icon { width: 70px; height: 70px; font-size: 1.6rem; }
        }

        @media (max-width: 576px) {
            .welcome-title { font-size: 1.8rem; }
            .welcome-header { padding: 2rem 1rem; }
            .features-grid { padding: 2rem 1rem; }
            .login-btn { padding: 10px 28px; font-size: .98rem; }
        }

        /* Respect reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            .bg-wheel { animation: none !important; }
            .login-btn, .welcome-header, .feature-item { transition: none !important; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo-section">
                <div class="logo-icon" aria-hidden="true">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>FRS Portal</h1>
                    <p>Course Registration System</p>
                </div>
            </div>
        </div>
    </header>

    <!-- decorative wheels behind content -->
    <div class="bg-wheel w1" aria-hidden="true"></div>
    <div class="bg-wheel w2" aria-hidden="true"></div>

    <!-- wave layer for button ripple -->
    <div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

    <div class="main-container">
        <div class="container">
            <div class="welcome-card">
                <div class="welcome-header">
                    <h1 class="welcome-title">Welcome to the FRS Portal</h1>
                    <p class="welcome-subtitle">
                        Course Registration System — manage course registration effortlessly with a clean, modern interface.
                    </p>
                    <a href="{{ route('login') }}" class="login-btn" data-wave>
                        <i class="fas fa-sign-in-alt"></i><span>Sign In</span>
                    </a>
                </div>

                <div class="features-grid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon" aria-hidden="true">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h3 class="feature-title">Course Management</h3>
                                <p class="feature-desc">View and manage detailed course information including capacity, credits, and prerequisites.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon" aria-hidden="true">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <h3 class="feature-title">Online Enrollment</h3>
                                <p class="feature-desc">Enroll in courses quickly with a streamlined process and clear status feedback.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <div class="feature-icon" aria-hidden="true">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="feature-title">Progress Monitoring</h3>
                                <p class="feature-desc">Track your registered courses, credits, and completion progress from your dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="info-section">
                    <h3 class="info-title">System Access</h3>
                    <p class="info-text">
                        Use your registered account to sign in — students, lecturers, and administrators will have role-appropriate permissions after authentication.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4" data-wave>
                        <i class="fas fa-arrow-right me-2"></i>Enter the System
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} FRS Portal - Course Registration System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Respect reduced motion
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Create wave ripple on elements marked with data-wave
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

        // tweak wheel animations randomly for slight variation
        document.querySelectorAll('.bg-wheel').forEach((wheel, idx) => {
            if(reduceMotion) { wheel.style.animation = 'none'; return; }
            const base = 100 + Math.random()*80;
            wheel.style.animationDuration = base + 's';
            if(Math.random() < 0.5) wheel.style.animationDirection = 'reverse';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
