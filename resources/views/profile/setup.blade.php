@extends('layouts.app')

@section('title', 'Profile Setup - Course System')

@section('content')
<!-- Profile Setup UI (matches student.blade.php style) -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
        --bg: #cfeeff;
        --card-radius: 14px;
        --glass: rgba(255,255,255,0.98);
        --accent: #0d6efd;
    }

    body {
        font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
        min-height: 100vh;
        margin: 0;
        color: #0b2545;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }

    /* decorative background wheels */
    .bg-wheel { position: fixed; border-radius: 50%; pointer-events: none; filter: blur(12px); mix-blend-mode: lighten; z-index: -2; }
    .bg-wheel.w1 { width: 520px; height: 520px; right: -180px; top: -120px; background: conic-gradient(from 120deg, rgba(13,110,253,0.28), rgba(3,169,244,0.18)); opacity: 0.09; animation: rotate-slow 120s linear infinite; }
    .bg-wheel.w2 { width: 360px; height: 360px; left: -140px; bottom: -80px; background: conic-gradient(from 210deg, rgba(0,123,255,0.24), rgba(0,200,255,0.12)); opacity: 0.06; animation: rotate-slow-rev 160s linear infinite; }
    @keyframes rotate-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    @keyframes rotate-slow-rev { from{transform:rotate(0deg)} to{transform:rotate(-360deg)} }

    /* wave ripples */
    .wave-layer { position: fixed; inset: 0; pointer-events: none; z-index: 1; }
    .wave { position: absolute; border-radius: 50%; width: 20px; height: 20px; transform: translate(-50%,-50%) scale(0.2); opacity: 0; background: rgba(13,110,253,0.12); animation: wave-expand 2.2s cubic-bezier(.2,.8,.2,1) forwards; }
    @keyframes wave-expand {
        0% { transform: translate(-50%,-50%) scale(0.2); opacity: 0.5; }
        60% { opacity: 0.18; }
        100% { transform: translate(-50%,-50%) scale(12); opacity: 0; }
    }

    /* layout */
    .setup-wrap { position: relative; z-index: 2; padding: 2.0rem 0; }
    .page-header { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom: 1rem; }
    .page-header h1 { margin:0; font-weight:700; color:#021d36; letter-spacing:-0.5px; }
    .header-underline { height:3px; width:64px; background: linear-gradient(90deg,var(--accent), #00c6ff); border-radius:8px; opacity:0; transform-origin:left center; transition: opacity .22s ease, transform .22s ease; }
    .page-header:hover .header-underline { opacity:1; transform: scaleX(1.02); }

    /* cards */
    .system-card { border-radius: 12px; background: var(--glass); box-shadow: 0 10px 28px rgba(11,37,69,0.08); border: 1px solid rgba(11,37,69,0.04); }
    .card-body { padding: 1.35rem; }

    /* form elements */
    label { font-weight:600; color:#06325a; }
    .form-control { border-radius: 8px; border: 1px solid rgba(11,37,69,0.08); padding: .55rem .75rem; }
    textarea.form-control { min-height: 100px; }

    /* headings bold Poppins black */
    .profile-title, .setup-title { font-family: 'Poppins', inherit; font-weight: 700; color: #000; }

    /* buttons */
    .btn-wave { border-radius: 10px; padding: .55rem .9rem; border: none; background: linear-gradient(90deg,var(--accent), #3aa0ff); color: #fff; font-weight:600; cursor: pointer; box-shadow: 0 8px 20px rgba(13,110,253,0.12); transition: transform .16s ease, box-shadow .16s ease; }
    .btn-wave:active { transform: translateY(1px) scale(.998); }
    .btn-outline { border-radius: 10px; padding: .45rem .8rem; border: 1px solid rgba(3,57,102,0.12); background: transparent; color: #06325a; font-weight:600; }

    /* error messages */
    .text-danger { color: #c92a2a; }

    /* thick black outlines for table-like wrappers (keep consistent even if no actual tables here) */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        outline: 6px solid rgba(0,0,0,1); /* thick black */
        border: 3px solid rgba(0,0,0,1);
        background: #fff;
    }

    /* responsiveness */
    @media (max-width: 767.98px) {
        .page-header { flex-direction: column; align-items:flex-start; gap:.5rem; }
    }
</style>

<!-- decorative wheels -->
<div class="bg-wheel w1" aria-hidden="true"></div>
<div class="bg-wheel w2" aria-hidden="true"></div>

<!-- wave layer -->
<div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

<div class="setup-wrap container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header">
                <div>
                    <h1>Profile Setup</h1>
                    <div class="header-underline" aria-hidden="true"></div>
                    <small class="text-muted">Complete your profile so the system can personalise your experience</small>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Signed in as</small>
                    <strong>{{ $user->email }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card system-card">
                <div class="card-header">
                    <h5 class="mb-0 setup-title">Complete Your {{ ucfirst($user->role) }} Profile</h5>
                </div>

                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif

                    {{-- show validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.store') }}">
                        @csrf

                        @if($user->role === 'student')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="student_name"
                                       value="{{ old('student_name', optional($user->student)->student_name ?? $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                       value="{{ old('phone', optional($user->student)->phone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth"
                                       value="{{ old('date_of_birth', optional($user->student)->date_of_birth) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address', optional($user->student)->address) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Admission Year</label>
                                <input type="number" class="form-control" name="admission_year"
                                       value="{{ old('admission_year', optional($user->student)->admission_year ?? date('Y')) }}"
                                       min="2000" max="{{ date('Y') + 1 }}" required>
                            </div>
                        @endif

                        @if($user->role === 'lecturer')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="lecturer_name"
                                       value="{{ old('lecturer_name', optional($user->lecturer)->lecturer_name ?? $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                       value="{{ old('phone', optional($user->lecturer)->phone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department"
                                       value="{{ old('department', optional($user->lecturer)->department) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Laboratorium</label>
                                <input type="text" class="form-control" name="laboratorium"
                                       value="{{ old('laboratorium', optional($user->lecturer)->laboratorium) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address', optional($user->lecturer)->address) }}</textarea>
                            </div>
                        @endif

                        @if($user->role === 'admin')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="admin_name"
                                       value="{{ old('admin_name', optional($user->admin)->admin_name ?? $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department"
                                       value="{{ old('department', optional($user->admin)->department) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address', optional($user->admin)->address) }}</textarea>
                            </div>
                        @endif

                        <div class="d-flex gap-2 align-items-center" style="margin-top:1.1rem;">
                            <button type="submit" class="btn-wave" data-wave>Save Profile</button>
                            <a href="{{ url()->previous() }}" class="btn-outline" role="button">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS: wave ripple on buttons, wheel randomization, keyboard accessibility -->
<script>
    // respect reduced motion
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // wave on button hover/click (data-wave)
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
        const x = (pageX / window.innerWidth) * 100;
        const y = (pageY / window.innerHeight) * 100;
        wave.style.left = x + '%';
        wave.style.top = y + '%';
        layer.appendChild(wave);
        setTimeout(()=> wave.remove(), 2400);
    }

    // subtle animation speed randomization for wheels
    document.querySelectorAll('.bg-wheel').forEach((wheel, idx) => {
        if(reduceMotion) { wheel.style.animation = 'none'; return; }
        const base = 100 + Math.random()*80;
        wheel.style.animationDuration = base + 's';
        if(Math.random() < 0.5) wheel.style.animationDirection = 'reverse';
    });

    // keyboard accessibility for buttons with data-wave
    document.querySelectorAll('[data-wave]').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });
</script>
@endsection
