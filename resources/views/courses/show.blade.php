@extends('layouts.app')

@section('title', 'Course Details - Course System')

@section('content')
<!-- Course Details UI — matched to other pages (Poppins, blue bg, wheels, waves, bold headings) -->
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

    /* decorative wheels */
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
    .card-header h3, .card-header h5 { margin:0; font-weight:700; color:#000; }

    .muted-small { font-size: .9rem; color: #6c757d; }

    /* enrolled list */
    .list-group-item { background: transparent; border-bottom: 1px dashed rgba(3,40,70,0.05); padding-left: 0; padding-right: 0; }

    /* progress */
    .progress { height: 10px; background: rgba(3,40,70,0.06); border-radius: 8px; overflow: hidden; }
    .progress-bar { background: linear-gradient(90deg, var(--accent), #00a6ff); }

    /* buttons */
    .btn-wave {
        border-radius: 10px;
        padding: .45rem .75rem;
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
    .btn-outline {
        border-radius: 8px;
        padding: .38rem .65rem;
        border: 1px solid rgba(3,40,70,0.08);
        background: transparent;
        color: #06325a;
        font-weight:600;
    }

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
            <h1>Course Details</h1>
            <div class="header-underline" aria-hidden="true"></div>
            <small class="muted-small d-block">Detailed information about this course, enrollments, and status.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courses.edit', $course) }}" class="btn-wave" data-wave>
                <i class="fas fa-edit"></i><span>Edit Course</span>
            </a>
            <a href="{{ route('courses.index') }}" class="btn-outline" role="button" data-wave>
                <i class="fas fa-arrow-left me-2"></i>Back to Courses
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card system-card mb-3">
                <div class="card-header px-4 py-3">
                    <h3 class="card-title mb-0">{{ $course->code }} - {{ $course->name }}</h3>
                </div>
                <div class="card-body px-4 py-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Course Code:</strong>
                            <p class="mb-0">{{ $course->code }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Credits:</strong>
                            <p class="mb-0">{{ $course->credits }} {{ $course->credits == 1 ? 'Credit' : 'Credits' }}</p>
                        </div>
                    </div>

                    @if($course->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="mb-0">{{ $course->description }}</p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Maximum Students:</strong>
                            <p class="mb-0">{{ $course->max_students }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Currently Enrolled:</strong>
                            <p class="mb-0">{{ $course->enrolled_count ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge {{ $course->isAvailable() ? 'bg-success' : 'bg-secondary' }}">
                            {{ $course->isAvailable() ? 'Available' : 'Not Available' }}
                        </span>
                    </div>

                    @if($course->prerequisites->count() > 0)
                    <div class="mb-3">
                        <strong>Prerequisites:</strong>
                        <div class="mt-2">
                            @foreach($course->prerequisites as $prerequisite)
                                <span class="badge bg-info me-2 mb-1">
                                    {{ $prerequisite->code }} - {{ $prerequisite->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card system-card mb-3">
                <div class="card-header px-3 py-2">
                    <h5 class="card-title mb-0">Enrolled Students</h5>
                </div>
                <div class="card-body">
                    @if($course->enrolledStudents->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($course->enrolledStudents as $student)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-fill">
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($course->enrolledStudents->count() >= 10)
                            <div class="text-center mt-2">
                                <small class="text-muted">Showing first 10 students</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted mb-0">No students enrolled yet.</p>
                    @endif
                </div>
            </div>

            <div class="card system-card mt-3">
                <div class="card-header px-3 py-2">
                    <h5 class="card-title mb-0">Menu</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0 text-primary">{{ $course->enrolled_count ?? 0 }}</div>
                            <small class="text-muted">Enrolled</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">{{ $course->max_students - ($course->enrolled_count ?? 0) }}</div>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>

                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $course->max_students > 0 ? (($course->enrolled_count ?? 0) / $course->max_students) * 100 : 0 }}%">
                        </div>
                    </div>
                    <small class="text-muted">Enrollment Progress</small>
                </div>
            </div>
        </div>
    </div>

    @if($course->enrollments()->exists())
        <div class="alert alert-info mt-4">
            <strong>Note:</strong> This course cannot be deleted because it has enrolled students.
        </div>
    @endif
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
    document.querySelectorAll('.btn-wave, .btn-outline').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });
</script>
@endsection
