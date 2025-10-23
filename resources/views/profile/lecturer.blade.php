@extends('layouts.app')

@section('title', 'Lecturer Profile - Course System')

@section('content')
<!-- Lecturer Profile UI (matches student & setup pages style) -->
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

    /* container */
    .profile-wrap { position: relative; z-index: 2; padding: 2.0rem 0; }

    /* header */
    .page-header { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom: 1rem; }
    .page-header h1 { margin:0; font-weight:700; color:#021d36; letter-spacing:-0.5px; }
    .header-underline { height:3px; width:64px; background: linear-gradient(90deg,var(--accent), #00c6ff); border-radius:8px; opacity:0; transform-origin:left center; transition: opacity .22s ease, transform .22s ease; }
    .page-header:hover .header-underline { opacity:1; transform: scaleX(1.02); }

    .system-card { border-radius: 12px; background: var(--glass); box-shadow: 0 10px 28px rgba(11,37,69,0.08); border: 1px solid rgba(11,37,69,0.04); }
    .card-body { padding: 1.35rem; }

    .profile-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; align-items: start; }
    @media (max-width: 991.98px) { .profile-grid { grid-template-columns: 1fr; } }

    .profile-title { font-family: 'Poppins', inherit; font-weight:700; color: #000; }
    .profile-item { margin-bottom: .85rem; color: #06325a; }
    .profile-item strong { color: #021d36; font-weight:600; }

    /* buttons */
    .btn-wave { border-radius: 10px; padding: .55rem .9rem; border: none; background: linear-gradient(90deg,var(--accent), #3aa0ff); color: #fff; font-weight:600; cursor: pointer; box-shadow: 0 8px 20px rgba(13,110,253,0.12); transition: transform .16s ease, box-shadow .16s ease; }
    .btn-wave:active { transform: translateY(1px) scale(.998); }
    .btn-outline { border-radius: 10px; padding: .45rem .8rem; border: 1px solid rgba(3,57,102,0.12); background: transparent; color: #06325a; font-weight:600; }

    /* courses table */
    .enroll-card .card-header, .menu-card .card-header, .system-info-card .card-header { background: transparent; border-bottom: none; }
    .enroll-card thead th {
        background: linear-gradient(90deg,#0b77d9 0%, #1391ff 100%);
        color: #fff;
        font-weight:700;
        padding: .85rem 1rem;
        border-bottom: 0;
    }

    .table { width:100%; border-collapse: collapse; }
    .table thead th { text-align:left; font-weight:700; padding: .6rem 1rem; }
    .table tbody td { padding: .6rem 1rem; color: #06325a; border-bottom: 1px solid rgba(6,40,80,0.04); }

    /* THICK BLACK OUTLINE for tables */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        outline: 6px solid rgba(0,0,0,1);
        border: 3px solid rgba(0,0,0,1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        background: white;
    }

    .system-info-card, .enroll-card, .menu-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,255,0.98));
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
    }

    /* small helpers */
    .meta { font-size: .95rem; color: #335; }

</style>

<!-- decorative background wheels -->
<div class="bg-wheel w1" aria-hidden="true"></div>
<div class="bg-wheel w2" aria-hidden="true"></div>

<!-- wave layer -->
<div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

<div class="profile-wrap container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header">
                <div>
                    <h1>Lecturer Profile</h1>
                    <div class="header-underline" aria-hidden="true"></div>
                    <small class="text-muted">Personal information and teaching summary</small>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">Signed in as</small>
                    <strong>{{ Auth::user()->email }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="profile-grid">
                <!-- left: main profile + courses taught -->
                <div>
                    <div class="card system-card">
                        <div class="card-header">
                            <h5 class="mb-0 profile-title">Profile Details</h5>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight:700; margin-bottom:.5rem;">{{ Auth::user()->lecturer->lecturer_name }}</h5>
                            <p class="profile-item"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p class="profile-item"><strong>Phone:</strong> {{ Auth::user()->lecturer->phone ?? '-' }}</p>
                            <p class="profile-item"><strong>Department:</strong> {{ Auth::user()->lecturer->department ?? '-' }}</p>
                            <p class="profile-item"><strong>Laboratorium:</strong> {{ Auth::user()->lecturer->laboratorium ?? '-' }}</p>
                            <p class="profile-item"><strong>Address:</strong> {{ Auth::user()->lecturer->address ?? '-' }}</p>

                            <div class="d-flex gap-2" style="margin-top:1rem;">
                                <a href="{{ route('profile.setup') }}" class="btn-wave" role="button" data-wave>Edit Profile</a>
                                <a href="{{ route('courses.index') }}" class="btn-outline" role="button" data-wave>Manage Courses</a>
                            </div>
                        </div>
                    </div>

                    {{-- Courses taught preview --}}
                    <div class="card system-card enroll-card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0 enroll-title">Courses I Teach</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $courses = Auth::user()->lecturer->courses()->take(8)->get();
                                $total = Auth::user()->lecturer->courses()->count();
                            @endphp

                            @if($total > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Code</th>
                                                <th>Credits</th>
                                                <th>Students</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($courses as $course)
                                            <tr>
                                                <td>{{ $course->name }}</td>
                                                <td>{{ $course->code }}</td>
                                                <td>{{ $course->credits }}</td>
                                                <td>{{ $course->students()->count() ?? 0 }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="{{ route('courses.index') }}" class="btn btn-primary" data-wave>View All Courses ({{ $total }})</a>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-text" style="font-size: 3rem; color: rgba(11,37,69,0.18);"></i>
                                    <h6 class="mt-3 text-muted">You are not assigned to any course yet</h6>
                                    <a href="{{ route('courses.index') }}" class="btn btn-primary mt-2" data-wave>Manage Courses</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- right: system info and quick actions -->
                <aside>
                    <div class="card system-card system-info-card">
                        <div class="card-header"><h5 class="mb-0">System Information</h5></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Account:</strong> {{ Auth::user()->email }}</li>
                                <li class="mb-2"><strong>Member Since:</strong> {{ Auth::user()->created_at->format('M Y') }}</li>
                                <li class="mb-2"><strong>Last Login:</strong> {{ now()->format('M d, Y') }}</li>
                            </ul>
                            <hr>
                            <h6>Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.setup') }}" class="btn btn-wave" data-wave>Edit Profile</a>
                                <a href="{{ route('courses.create') }}" class="btn btn-outline" data-wave>Create Course</a>
                            </div>
                        </div>
                    </div>

                    <div class="card system-card mt-3">
                        <div class="card-header"><h5 class="mb-0">Announcements</h5></div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0" role="alert">
                                <small><strong>Course Registration:</strong> FRS is open now!</small>
                            </div>
                        </div>
                    </div>
                </aside>
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
