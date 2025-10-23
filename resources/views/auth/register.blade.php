@extends('layouts.app')

@section('title', 'Register - Course System')

@section('content')
<!-- Register UI — consistent with other pages (Poppins, blue bg, wheels, waves, bold headings) -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
        --bg: #cfeeff;
        --accent: #0d6efd;
    }

    body {
        font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
        background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
        color: #021d36;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }

    .page-wrap { position: relative; z-index: 2; padding: 2.25rem 0; }

    .card-auth {
        max-width: 520px;
        margin: 2.5rem auto;
        border-radius: 12px;
        background: rgba(255,255,255,0.98);
        box-shadow: 0 12px 32px rgba(3,40,70,0.08);
        border: 1px solid rgba(3,40,70,0.06);
        overflow: hidden;
    }

    .card-header-auth {
        background: linear-gradient(135deg, var(--accent), #0b77d9);
        color: #fff;
        padding: 1.25rem 1.5rem;
        text-align: center;
    }

    .card-body-auth {
        padding: 1.5rem;
    }

    .form-label { font-weight:600; color:#06325a; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid rgba(3,40,70,0.08);
        padding: .55rem .75rem;
        font-family: inherit;
    }
    .invalid-feedback { color: #c92a2a; font-size:.9rem; }

    .btn-wave {
        border-radius: 10px;
        padding: .55rem .95rem;
        border: none;
        background: linear-gradient(90deg,var(--accent), #3aa0ff);
        color: #fff;
        font-weight:700;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(3,40,70,0.12);
        transition: transform .14s ease, box-shadow .14s ease;
        display:inline-flex;
        gap:.6rem;
        align-items:center;
        justify-content:center;
        width: 100%;
    }
    .btn-wave:active { transform: translateY(1px) scale(.998); }

    .muted-small { font-size:.92rem; color: #6c757d; }

    /* decorative wheels */
    .bg-wheel { position: fixed; border-radius: 50%; pointer-events: none; filter: blur(12px); mix-blend-mode: lighten; z-index: 0; opacity: 0.08; }
    .bg-wheel.w1 { width: 640px; height: 640px; right: -220px; top: -140px; background: conic-gradient(from 110deg, rgba(13,110,253,0.36), rgba(3,169,244,0.22)); animation: slow-rotate 120s linear infinite; }
    .bg-wheel.w2 { width: 420px; height: 420px; left: -140px; bottom: -100px; background: conic-gradient(from 210deg, rgba(0,123,255,0.24), rgba(0,200,255,0.12)); animation: slow-rotate-rev 160s linear infinite; }
    @keyframes slow-rotate { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    @keyframes slow-rotate-rev { from{transform:rotate(0deg)} to{transform:rotate(-360deg)} }

    /* wave layer */
    .wave-layer { position: fixed; inset: 0; pointer-events: none; z-index: 1; }
    .wave { position: absolute; border-radius: 50%; width: 20px; height: 20px; transform: translate(-50%,-50%) scale(0.2); opacity: 0; background: rgba(13,110,253,0.12); animation: wave-expand 2.2s cubic-bezier(.2,.8,.2,1) forwards; }
    @keyframes wave-expand {
        0% { transform: translate(-50%,-50%) scale(0.2); opacity: 0.5; }
        60% { opacity: 0.18; }
        100% { transform: translate(-50%,-50%) scale(12); opacity: 0; }
    }

    @media (max-width: 576px) {
        .card-auth { margin: 1.25rem; }
        .card-header-auth h4 { font-size: 1.1rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .bg-wheel { animation: none !important; }
        .btn-wave { transition: none !important; }
    }
</style>

<!-- decorative wheels -->
<div class="bg-wheel w1" aria-hidden="true"></div>
<div class="bg-wheel w2" aria-hidden="true"></div>

<!-- wave layer -->
<div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

<div class="page-wrap">
    <div class="card-auth">
        <div class="card-header-auth">
            <h4 class="mb-0">Create an Account</h4>
        </div>

        <div class="card-body-auth">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           autofocus>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="lecturer" {{ old('role') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Choose the role for the new account.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required
                           autocomplete="new-password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Password must be at least 8 characters long.</div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="form-control"
                           required
                           autocomplete="new-password">
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn-wave" data-wave>
                        <i class="fas fa-user-plus"></i><span>Register</span>
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0 muted-small">Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS: wave ripple on buttons, wheel randomization, keyboard accessibility -->
<script>
    // Respect reduced motion
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Wave ripple on elements with data-wave
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

    // keyboard accessibility for action buttons
    document.querySelectorAll('.btn-wave').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });
</script>
@endsection
