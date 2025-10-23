@extends('layouts.app')

@section('title', 'Edit Course - Course System')

@section('content')
<!-- Edit Course UI — consistent with other pages (Poppins, blue bg, wheels, waves, bold headings) -->
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

    .page-header { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; margin-bottom:1.25rem; }
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
    .card-header h5 { margin:0; font-weight:700; color:#000; }

    /* form elements */
    .form-label { font-weight:600; color:#06325a; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid rgba(3,40,70,0.08);
        padding: .55rem .75rem;
        font-family: inherit;
    }
    .invalid-feedback { font-size: .9rem; color: #c92a2a; }

    /* buttons */
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
    }
    .btn-wave:active { transform: translateY(1px) scale(.998); }
    .btn-outline {
        border-radius: 10px;
        padding: .5rem .9rem;
        border: 1px solid rgba(3,40,70,0.08);
        background: transparent;
        color: #06325a;
        font-weight:600;
    }

    .muted-small { font-size: .9rem; color: #6c757d; }

    @media (max-width: 767.98px) {
        .page-header { flex-direction: column; align-items:flex-start; gap:.5rem; }
    }

    /* respect reduced motion */
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
            <h1>Edit Course</h1>
            <div class="header-underline" aria-hidden="true"></div>
            <small class="muted-small">Modify course details — changes will affect enrollments and listings.</small>
        </div>
        <div class="text-end">
            <a href="{{ route('courses.show', $course) }}" class="btn-outline" role="button" data-wave>
                <i class="fas fa-eye me-2"></i>View Course
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card system-card mb-3">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Course Information</h5>
                </div>
                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('courses.update', $course) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Course Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                       id="code" name="code" value="{{ old('code', $course->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Course Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $course->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="credits" class="form-label">Credits</label>
                                <input type="number" class="form-control @error('credits') is-invalid @enderror"
                                       id="credits" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="6" required>
                                @error('credits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_students" class="form-label">Maximum Students</label>
                                <input type="number" class="form-control @error('max_students') is-invalid @enderror"
                                       id="max_students" name="max_students" value="{{ old('max_students', $course->max_students) }}" min="1" required>
                                @error('max_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if($course->enrolled_count > 0)
                                    <small class="text-muted">
                                        Current enrollment: {{ $course->enrolled_count }} students
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="prerequisites" class="form-label">Prerequisites</label>
                            <select class="form-select @error('prerequisites') is-invalid @enderror"
                                    id="prerequisites" name="prerequisites[]" multiple size="5">
                                @foreach($courses as $prereq)
                                    <option value="{{ $prereq->id }}"
                                        {{ in_array($prereq->id, old('prerequisites', $selectedPrerequisites)) ? 'selected' : '' }}>
                                        {{ $prereq->code }} - {{ $prereq->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prerequisites')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple prerequisites</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-wave" data-wave>
                                <i class="fas fa-save"></i><span>Update Course</span>
                            </button>

                            <a href="{{ route('courses.show', $course) }}" class="btn-outline" role="button" data-wave>
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card system-card mb-3">
                <div class="card-header px-3 py-2">
                    <h5 class="card-title mb-0">Current Course Info</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Code:</dt>
                        <dd class="col-sm-7">{{ $course->code }}</dd>

                        <dt class="col-sm-5">Credits:</dt>
                        <dd class="col-sm-7">{{ $course->credits }}</dd>

                        <dt class="col-sm-5">Max Students:</dt>
                        <dd class="col-sm-7">{{ $course->max_students }}</dd>

                        <dt class="col-sm-5">Enrolled:</dt>
                        <dd class="col-sm-7">{{ $course->enrolled_count ?? 0 }}</dd>

                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            <span class="badge {{ $course->isAvailable() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $course->isAvailable() ? 'Available' : 'Not Available' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            @if($course->prerequisites->count() > 0)
            <div class="card system-card mb-3">
                <div class="card-header px-3 py-2">
                    <h5 class="card-title mb-0">Current Prerequisites</h5>
                </div>
                <div class="card-body">
                    @foreach($course->prerequisites as $prerequisite)
                        <span class="badge bg-info text-dark me-2 mb-1" style="font-weight:600;">
                            {{ $prerequisite->code }} - {{ $prerequisite->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($course->enrollments()->exists())
            <div class="alert alert-warning mt-3">
                <strong>Warning:</strong> This course has enrolled students. Be careful when making changes that might affect them.
            </div>
            @endif
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

    // Randomize wheel animations slightly
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
