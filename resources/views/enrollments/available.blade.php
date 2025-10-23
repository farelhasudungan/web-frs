@extends('layouts.app')

@section('title', 'Available Courses - Course System')

@section('content')
<!-- Available Courses UI (matches Dashboard / Profile style):
     - Poppins font
     - bright light-blue background
     - decorative wheels
     - wave ripple on action buttons (data-wave)
     - bold headings Poppins black
     - course cards with thick black outline for strong contrast
-->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
        --bg: #cfeeff;
        --accent: #0d6efd;
        --card-radius: 12px;
    }

    body {
        font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
        color: #0b2545;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
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

    .wrap { position: relative; z-index: 2; padding: 2rem 0; }

    h1.page-title { font-weight:700; color:#021d36; letter-spacing:-0.4px; margin-bottom: .35rem; }
    .header-underline { height:3px; width:64px; background: linear-gradient(90deg,var(--accent), #00c6ff); border-radius:8px; opacity:0; transform-origin:left center; transition: opacity .22s ease, transform .22s ease; }
    .page-head:hover .header-underline { opacity:1; transform:scaleX(1.02); }

    /* course card with thick black outline (as requested) */
    .course-card {
        border-radius: var(--card-radius);
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,255,0.98));
        padding: 1.05rem;
        box-shadow: 0 10px 28px rgba(11,37,69,0.06);
        /* strong black outline */
        outline: 6px solid rgba(0,0,0,1);
        border: 3px solid rgba(0,0,0,1);
        height: 100%;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
    }

    .course-title { font-size: 1.05rem; font-weight:700; color:#021d36; margin-bottom: .35rem; }
    .course-desc { color:#06325a; margin-bottom: .6rem; font-size: .95rem; }
    .meta { color:#06325a; font-weight:600; margin-bottom:.6rem; }

    .prereq { color:#06325a; font-size:.9rem; margin-bottom:.5rem; }

    /* buttons */
    .btn-enroll {
        border-radius: 10px;
        padding: .5rem .8rem;
        border: none;
        background: linear-gradient(90deg,var(--accent), #3aa0ff);
        color: #fff;
        font-weight:700;
        cursor: pointer;
        transition: transform .14s ease, box-shadow .14s ease;
    }
    .btn-enroll:active { transform: translateY(1px) scale(.998); }
    .btn-disabled { border-radius:10px; padding:.45rem .75rem; background: #ffd86b; color: #06325a; font-weight:700; border:none; }

    .alert-warning { background: #fff3cd; color:#856404; border:1px solid rgba(133,100,4,0.08); padding:.6rem .9rem; border-radius:8px; }

    /* layout */
    .card-meta { margin-top: .6rem; display:flex; gap: .5rem; flex-wrap:wrap; align-items:center; }
    .badge-slot { background: #f1f8ff; color:#06325a; padding:.25rem .5rem; border-radius:6px; font-weight:600; border:1px solid rgba(6,80,140,0.06); }

    @media (max-width: 575.98px) {
        .course-card { outline-width: 4px; border-width: 2px; padding:.8rem; }
    }

</style>

<!-- decorative wheels -->
<div class="bg-wheel w1" aria-hidden="true"></div>
<div class="bg-wheel w2" aria-hidden="true"></div>

<div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

<div class="wrap container">
    <div class="row mb-3 page-head">
        <div class="col-12 d-flex justify-content-between align-items-end">
            <div>
                <h1 class="page-title">Available Courses</h1>
                <div class="header-underline" aria-hidden="true"></div>
                <small class="text-muted">Browse and enroll in active courses</small>
            </div>
            <div class="text-end">
                <small class="text-muted d-block">Signed in as</small>
                <strong>{{ Auth::user()->email }}</strong>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-md-6 col-lg-6">
            <div class="course-card">
                <div>
                    <div class="course-title">{{ $course->code }} — {{ $course->name }}</div>
                    <div class="course-desc">{{ \Illuminate\Support\Str::limit($course->description, 240) }}</div>

                    <div class="card-meta">
                        <div class="meta"><strong>Credits:</strong> {{ $course->credits }}</div>
                        <div class="badge-slot"><strong>Slots:</strong>
                            @php
                                $available = max(0, ($course->max_students ?? 0) - ($course->enrolled_count ?? 0));
                            @endphp
                            {{ $available }}/{{ $course->max_students ?? '-' }}
                        </div>
                    </div>

                    @if($course->prerequisites && $course->prerequisites->count() > 0)
                        <div class="prereq"><strong>Prerequisites:</strong> {{ $course->prerequisites->pluck('code')->implode(', ') }}</div>
                    @endif

                    @if(!empty($course->notes))
                        <div class="prereq"><small class="text-muted">{{ $course->notes }}</small></div>
                    @endif
                </div>

                <div>
                    @if(!empty($course->can_enroll) && $course->can_enroll)
                        <form action="{{ route('enrollments.enroll', $course) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-enroll w-100" data-wave>Enroll</button>
                        </form>
                    @else
                        <div class="alert-warning mt-2">
                            <strong>Cannot enroll</strong>
                            @if(!empty($course->enrollment_errors) && is_array($course->enrollment_errors))
                                : {{ implode(', ', $course->enrollment_errors) }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card system-card">
                <div class="card-body text-center">
                    <i class="bi bi-book" style="font-size: 3rem; color: rgba(11,37,69,0.18);"></i>
                    <h6 class="mt-3 text-muted">No available courses found</h6>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- pagination (if provided by controller) --}}
    @if(method_exists($courses, 'links'))
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $courses->links() }}
        </div>
    </div>
    @endif

</div>

<!-- JS: button waves & wheel animation subtle randomization + keyboard accessibility -->
<script>
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // wave on buttons when hovered/clicked (data-wave)
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

    // wheel animation randomization
    document.querySelectorAll('.bg-wheel').forEach((wheel, idx) => {
        if(reduceMotion) { wheel.style.animation = 'none'; return; }
        const base = 100 + Math.random()*80;
        wheel.style.animationDuration = base + 's';
        if(Math.random() < 0.5) wheel.style.animationDirection = 'reverse';
    });

    // keyboard accessibility for enroll buttons
    document.querySelectorAll('.btn-enroll').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });
</script>
@endsection
