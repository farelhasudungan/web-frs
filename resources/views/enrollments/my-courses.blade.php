@extends('layouts.app')

@section('title', 'My Courses - Course System')

@section('content')
<!-- My Courses page — visual parity with other pages (Poppins, blue bg, wheels, waves, bold headings) -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
        --bg: #cfeeff;
        --accent: #0d6efd;
        --card-radius: 12px;
        --glass: rgba(255,255,255,0.98);
        --card-shadow: 0 10px 28px rgba(3,40,70,0.06);
    }

    body {
        font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
        background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
        color: #021d36;
        -webkit-font-smoothing:antialiased;
        -moz-osx-font-smoothing:grayscale;
    }

    .page-wrap { position: relative; z-index: 2; padding: 2.25rem 0; }

    .page-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.25rem; }
    .page-header h1 { margin:0; font-weight:700; color:#021d36; font-size:1.6rem; }
    .header-underline { height:3px; width:64px; background: linear-gradient(90deg,var(--accent), #00c6ff); border-radius:8px; opacity:0; transition: opacity .22s ease, transform .22s ease; }
    .page-header:hover .header-underline { opacity:1; transform: scaleX(1.02); }

    /* decorative background wheels (subtle) */
    .bg-wheel { position: fixed; border-radius: 50%; pointer-events: none; filter: blur(14px); mix-blend-mode: lighten; z-index: 0; opacity: 0.08; }
    .bg-wheel.w1 { width: 640px; height: 640px; right: -220px; top: -140px; background: conic-gradient(from 110deg, rgba(13,110,253,0.36), rgba(3,169,244,0.22)); animation: slow-rotate 120s linear infinite; }
    .bg-wheel.w2 { width: 420px; height: 420px; left: -140px; bottom: -100px; background: conic-gradient(from 210deg, rgba(0,123,255,0.24), rgba(0,200,255,0.12)); animation: slow-rotate-rev 160s linear infinite; }
    @keyframes slow-rotate { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    @keyframes slow-rotate-rev { from{transform:rotate(0deg)} to{transform:rotate(-360deg)} }

    /* wave layer for button ripple */
    .wave-layer { position: fixed; inset: 0; pointer-events: none; z-index: 1; }
    .wave { position: absolute; border-radius: 50%; width: 20px; height: 20px; transform: translate(-50%,-50%) scale(0.2); opacity: 0; background: rgba(13,110,253,0.12); animation: wave-expand 2.2s cubic-bezier(.2,.8,.2,1) forwards; }
    @keyframes wave-expand {
        0% { transform: translate(-50%,-50%) scale(0.2); opacity: 0.5; }
        60% { opacity: 0.18; }
        100% { transform: translate(-50%,-50%) scale(12); opacity: 0; }
    }

    /* cards */
    .system-card {
        background: var(--glass);
        border-radius: var(--card-radius);
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(3,40,70,0.06);
    }
    .card-header h5 { margin:0; font-weight:700; color:#000; }

    /* table outline consistent with other pages */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        outline: 4px solid rgba(0,0,0,0.9);
        border: 2px solid rgba(0,0,0,0.95);
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        background: white;
    }

    .table { margin-bottom: 0; }
    .table thead th {
        background: linear-gradient(90deg, #0b77d9 0%, #1391ff 100%);
        color: #fff;
        font-weight:700;
        border: none;
    }
    .table tbody td { color: #06325a; vertical-align: middle; }

    /* buttons */
    .btn-wave {
        border-radius: 10px;
        padding: .38rem .65rem;
        border: none;
        background: linear-gradient(90deg,var(--accent), #3aa0ff);
        color: #fff;
        font-weight:700;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(3,40,70,0.12);
        transition: transform .14s ease, box-shadow .14s ease;
        display:inline-flex;
        gap:.4rem;
        align-items:center;
        justify-content:center;
    }
    .btn-wave:active { transform: translateY(1px) scale(.998); }
    .btn-danger-sm {
        border-radius: 8px;
        padding: .28rem .6rem;
        background: linear-gradient(90deg,#e74c3c,#ff6b6b);
        color: white;
        border: none;
        font-weight:700;
    }

    .muted-small { font-size: .9rem; color: #6c757d; }

    @media (max-width: 767.98px) {
        .page-header { flex-direction: column; align-items:flex-start; gap:.5rem; }
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

<div class="page-wrap container">
    <div class="page-header">
        <div>
            <h1>My Courses</h1>
            <div class="header-underline" aria-hidden="true"></div>
            <small class="muted-small d-block">Your current and completed course list — you can drop current enrollments here.</small>
        </div>
        <div class="d-flex gap-2">
            <!-- kept simple: no extra buttons needed, but we keep UI consistent -->
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h3 class="mb-3">Currently Enrolled</h3>

            @if($enrolledCourses->count() > 0)
            <div class="table-responsive mb-4">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Credits</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledCourses as $course)
                        <tr>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->credits }}</td>
                            <td class="text-end">
                                <form action="{{ route('enrollments.drop', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to drop this course?');">
                                    @csrf
                                    <button type="submit" class="btn-danger-sm" data-wave>
                                        <i class="fas fa-times-circle me-1"></i>Drop
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">You are not currently enrolled in any courses.</p>
            @endif

            <h3 class="mt-4 mb-3">Completed Courses</h3>

            @if($completedCourses->count() > 0)
            <div class="table-responsive mb-4">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Credits</th>
                            <th>Grade</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedCourses as $course)
                        <tr>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->credits }}</td>
                            <td>{{ $course->pivot->grade ?? 'N/A' }}</td>
                            <td>{{ $course->pivot->completed_at ? $course->pivot->completed_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">You have not completed any courses yet.</p>
            @endif
        </div>
    </div>
</div>

<!-- JS: wave ripple on buttons, wheel randomization, keyboard accessibility (functionality preserved) -->
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
    document.querySelectorAll('.btn-wave, .btn-danger-sm, .btn-outline').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });
</script>
@endsection
