@extends('layouts.app')

@section('title', 'Dashboard - Course System')

@section('content')
<!-- Enhanced Dashboard Blade (stardust removed + brighter blue background + high-contrast tables + hover white text on menu)
     Updated: BLACK thick outlines around each table for high contrast
     Modified: Recent Activities cleaned, limited to max 3 items, nicer circle colors; System Info shifted slightly to the right; Recent Activities column widened. -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root{
        /* brighter light blue background */
        --bg: #cfeeff; /* lebih biru muda dan cerah */
        --card-radius: 14px;
        --glass: rgba(255,255,255,0.98);
        --glass-strong: rgba(255,255,255,0.99);
        --accent: #0d6efd;

        /* Wheel config (kept) */
        --wheel1-size: 680px;
        --wheel1-right: -220px;
        --wheel1-top: -120px;
        --wheel1-color-a: rgba(13,110,253,0.36);
        --wheel1-color-b: rgba(3,169,244,0.24);
        --wheel1-opacity: 0.09;
        --wheel1-speed: 110s;

        --wheel2-size: 420px;
        --wheel2-left: -120px;
        --wheel2-bottom: -80px;
        --wheel2-color-a: rgba(0,123,255,0.24);
        --wheel2-color-b: rgba(0,200,255,0.12);
        --wheel2-opacity: 0.06;
        --wheel2-speed: 160s;
    }

    body {
        font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        background: linear-gradient(180deg, var(--bg) 0%, #eaf9ff 100%);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
        color: #0b2545;
    }

    /* big subtle rotating wheels (kept) */
    .bg-wheel{ position: fixed; border-radius:50%; pointer-events:none; filter: blur(14px); mix-blend-mode: lighten; z-index: -2; }
    .bg-wheel.w1{ width:var(--wheel1-size); height:var(--wheel1-size); background: conic-gradient(from 120deg, var(--wheel1-color-a), var(--wheel1-color-b), var(--wheel1-color-a)); right:var(--wheel1-right); top:var(--wheel1-top); animation: slow-rotate var(--wheel1-speed) linear infinite; opacity:var(--wheel1-opacity);} 
    .bg-wheel.w2{ width:var(--wheel2-size); height:var(--wheel2-size); background: conic-gradient(from 210deg, var(--wheel2-color-a), var(--wheel2-color-b), var(--wheel2-color-a)); left:var(--wheel2-left); bottom:var(--wheel2-bottom); animation: slow-rotate-rev var(--wheel2-speed) linear infinite; opacity:var(--wheel2-opacity);} 
    @keyframes slow-rotate{ from{ transform: rotate(0deg);} to{ transform: rotate(360deg);} }
    @keyframes slow-rotate-rev{ from{ transform: rotate(0deg);} to{ transform: rotate(-360deg);} }

    /* wave effect (expanding circles) container */
    .wave-layer{ position: fixed; inset:0; pointer-events:none; z-index:1; }
    .wave{ position: absolute; border-radius:50%; width:20px; height:20px; transform: translate(-50%,-50%) scale(0.2); opacity:0.0; background: rgba(13,110,253,0.12); animation: wave-expand 2.2s cubic-bezier(.2,.8,.2,1) forwards; }
    @keyframes wave-expand{
        0%{ transform: translate(-50%,-50%) scale(0.2); opacity:0.5; }
        60%{ opacity:0.18; }
        100%{ transform: translate(-50%,-50%) scale(12); opacity:0; }
    }

    /* keep main content above waves */
    .dashboard-wrap{ position: relative; z-index: 2; }

    h1{ font-weight:700; letter-spacing: -0.5px; }
    small.text-muted{ color: rgba(11,37,69,0.6); }

    /* card base styling */
    .card, .system-card, .stat-card {
        border-radius: var(--card-radius);
        background: var(--glass);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        box-shadow: 0 10px 28px rgba(11,37,69,0.08);
        border: 1px solid rgba(11,37,69,0.04);
    }

    .stat-card .card-body{ padding: 1.35rem; }
    .stat-card h5{ font-weight:600; margin-bottom:0.25rem; color: #000; }
    .stat-card h2{ font-weight:700; margin:0; letter-spacing: -0.5px; color: #fff; }

    .bg-primary-gradient{ background: linear-gradient(135deg,#0d6efd 0%, #3aa0ff 100%); }
    .bg-success-gradient{ background: linear-gradient(135deg,#198754 0%, #4bd08f 100%); }
    .bg-info-gradient{ background: linear-gradient(135deg,#0dcaf0 0%, #7be6ff 100%); }
    .bg-warning-gradient{ background: linear-gradient(135deg,#ffc107 0%, #ffd86b 100%); }

    /* menu buttons (tabs) keep existing behavior */
    .menu-btn{ border-radius: 12px; padding: .6rem .9rem; border:1px solid rgba(11,37,69,0.06); background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.92)); transition: transform .22s cubic-bezier(.2,.9,.2,1), box-shadow .22s ease, border-color .18s ease, background-color .18s ease, color .18s ease; display:flex; gap:.6rem; align-items:center; justify-content:center; color:#06325a; }
    .menu-btn:hover{ transform: translateY(-6px) scale(1.01); box-shadow: 0 12px 30px rgba(11,37,69,0.08); border-color: rgba(11,37,69,0.12); }
    .menu-btn:active{ transform: translateY(-2px) scale(.995); }
    .menu-btn:focus-visible{ outline: 3px solid rgba(13,110,253,0.14); outline-offset: 4px; }

    /* Make menu label bold and black by default */
    .menu-btn span{ font-weight:700; font-family: 'Poppins', inherit; color: #000; transition: color .18s ease; }
    .menu-btn i{ transition: color .18s ease, opacity .18s ease; color: #06325a; }

    /* --- hover makes text & icon white for ALL menu-btns --- */
    .menu-btn:hover span,
    .menu-btn:focus span,
    .menu-btn.menu-accent:hover span,
    .menu-btn.menu-accent:focus span {
        color: #ffffff !important;
    }
    .menu-btn:hover i,
    .menu-btn:focus i,
    .menu-btn.menu-accent:hover i,
    .menu-btn.menu-accent:focus i {
        color: rgba(255,255,255,0.98) !important;
        opacity: 0.98;
    }
    /* ensure background on hover still gives good contrast */
    .menu-btn.menu-accent:hover,
    .menu-btn.menu-accent:focus {
        background: linear-gradient(135deg, var(--accent), #3aa0ff);
        color: white !important;
        border-color: rgba(255,255,255,0.08);
        animation: menuPulse 1.6s ease-in-out infinite;
    }

    @keyframes menuPulse {
        0% { box-shadow: 0 6px 18px rgba(13,110,253,0.10); transform: translateY(-6px) scale(1.01); }
        50% { box-shadow: 0 14px 36px rgba(58,160,255,0.14); transform: translateY(-8px) scale(1.012); }
        100% { box-shadow: 0 6px 18px rgba(13,110,253,0.10); transform: translateY(-6px) scale(1.01); }
    }

    .table-hover tbody tr:hover{ background: rgba(13,110,253,0.08); transform: translateX(2px); transition: all .18s ease; } /* stronger hover for contrast */
    .badge-status{ padding: .45rem .6rem; border-radius: 999px; font-weight:600; }
    .fade-in-up{ animation: fadeUp .7s cubic-bezier(.18,.9,.32,1) both; }
    @keyframes fadeUp{ from{ opacity:0; transform: translateY(8px); } to{ opacity:1; transform:none; } }

    @media (max-width: 767px){ .bg-wheel, .wave-layer{ display:none; } }

    .header-top{ display:flex; align-items:flex-end; gap:.8rem; }
    .header-underline{ height:3px; width:64px; background: linear-gradient(90deg,var(--accent), #00c6ff); border-radius:8px; opacity:0; transform-origin:left center; transition: opacity .22s ease, transform .22s ease; }
    .header-top:hover .header-underline{ opacity:1; transform: scaleX(1.02); }

    /* Make Menu and My Current Enrollments headings bold black in Poppins */
    .menu-title, .enroll-title {
        font-family: 'Poppins', inherit;
        font-weight: 700;
        color: #000;
    }

    /* --- Colored cards/tables (contrasting headers) --- */
    /* Menu card (soft cyan tint) */
    .menu-card {
        background: linear-gradient(180deg, rgba(235,249,255,0.98), rgba(220,245,255,0.98));
        border: 1px solid rgba(10,100,160,0.08);
    }
    .menu-card .card-header { background: transparent; border-bottom: none; }
    .menu-card thead th {
        background: linear-gradient(90deg, #0b6edc 0%, #0d6efd 100%);
        color: #fff;
        font-weight:700;
        border-bottom: 0;
    }
    .menu-card tbody td { color: #05283f; }

    /* Enrollment card (slightly stronger blue header for contrast) */
    .enroll-card {
        background: linear-gradient(180deg, rgba(230,244,255,0.98), rgba(215,238,255,0.98));
        border: 1px solid rgba(10,110,180,0.08);
    }
    .enroll-card thead th {
        background: linear-gradient(90deg, #0b77d9 0%, #1391ff 100%);
        color: #ffffff;
        font-weight:700;
        border-bottom: 0;
    }
    .enroll-card tbody td { color: #05314b; }

    /* System Information card (muted deeper blue header) */
    .system-info-card {
        background: linear-gradient(180deg, rgba(245,250,255,0.98), rgba(235,247,255,0.98));
        border: 1px solid rgba(8,90,150,0.06);
    }
    .system-info-card thead th {
        background: linear-gradient(90deg, #0a5ca8 0%, #0d79c6 100%);
        color: #fff;
        font-weight:700;
        border-bottom: 0;
    }
    .system-info-card tbody td { color: #06325a; }

    /* table rows: slightly transparent white to keep readability */
    .menu-card table tbody tr td,
    .enroll-card table tbody tr td,
    .system-info-card table tbody tr td {
        background: rgba(255,255,255,0.0);
    }

    /* ------------------ NEW: EXTRA-STRONG BLACK Outline + border for ALL tables ------------------ */
    /* Apply to any .table used in the dashboard */
    .table {
        border-collapse: separate; /* keep border radius effect by wrapping in .table-responsive */
        width: 100%;
    }

    /* Make the table wrapper have a bold solid BLACK outline for max contrast */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden; /* ensure rounded corners clip the table */
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        /* STRONG black outline (thick) */
        outline: 6px solid rgba(0,0,0,1); /* pure black, thick */
        outline-offset: 0;
        /* ensure visible border too */
        border: 3px solid rgba(0,0,0,1); /* pure black */
    }

    /* Also ensure each table's thead background and td contrast well with the black outline */
    .menu-card .table-responsive { /* slight tint behind outline for harmony */ }
    .enroll-card .table-responsive { /* no extra change required */ }
    .system-info-card .table-responsive { /* no extra change required */ }

    /* table header cells: stronger contrast and clear separators */
    .table thead th {
        padding: .75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    /* table body cells: add subtle dividing lines and stronger background contrast */
    .table tbody td {
        padding: .75rem 1rem;
        border-bottom: 1px dashed rgba(6,40,80,0.04);
        background: rgba(255,255,255,0.02);
    }

    /* Make sure striped / hover states are visible on bright background */
    .table.table-hover tbody tr:hover td {
        background: rgba(13,110,253,0.06);
    }

    /* Small responsive tweak so outlines don't overflow on small screens */
    @media (max-width: 575.98px) {
        .table-responsive { outline-width: 4px; border-width: 2px; }
    }

    /* Clean list activity styling */
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .activity-list li {
        padding: 0.6rem 0.25rem;
        border-bottom: 1px solid rgba(11,37,69,0.06);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.18s ease;
    }

    .activity-list li:last-child {
        border-bottom: none;
    }

    .activity-list li:hover {
        background: rgba(13,110,253,0.03);
        padding-left: 0.5rem;
        border-radius: 6px;
    }

    /* bigger, neater circular badge for activity icons */
    .activity-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
        color: #fff;
        box-shadow: 0 6px 18px rgba(11,37,69,0.08);
        border: 3px solid rgba(255,255,255,0.8);
    }

    /* defined palette for consistent colors */
    .activity-badge-primary{ background: linear-gradient(135deg,#0d6efd 0%,#3aa0ff 100%); }
    .activity-badge-success{ background: linear-gradient(135deg,#198754 0%,#4bd08f 100%); }
    .activity-badge-info{ background: linear-gradient(135deg,#0dcaf0 0%,#7be6ff 100%); }
    .activity-badge-warning{ background: linear-gradient(135deg,#ffc107 0%,#ffd86b 100%); color:#06325a; }

    .activity-content { flex:1; min-width:0; }
    .activity-text { font-size:0.95rem; color:#07203a; display:block; margin-bottom:0.15rem; font-weight:600; }
    .activity-time { font-size:0.78rem; color: rgba(11,37,69,0.5); }

    /* Slight right-shift for system info column (keeps responsive) */
    .system-info-column { display:flex; justify-content:flex-end; }
    .system-info-column .system-info-card { max-width: 100%; transform: translateX(8%); }

    /* responsive tweaks for when the layout stacks */
    @media (max-width: 767px){
        .system-info-column { justify-content:flex-start; }
        .system-info-column .system-info-card { transform:none; }
    }

</style>

<!-- background wheels -->
<div class="bg-wheel w1" aria-hidden="true"></div>
<div class="bg-wheel w2" aria-hidden="true"></div>

<!-- wave layer (kept) -->
<div class="wave-layer" id="waveLayer" aria-hidden="true"></div>

<div class="dashboard-wrap container py-4">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="header-top">
                        <h1 class="mb-0">Welcome, {{ Auth::user()->name }}!</h1>
                        <div class="header-underline" aria-hidden="true"></div>
                    </div>
                    <small class="text-muted">Course Registration System Dashboard</small>
                </div>

                <div class="text-end">
                    <small class="text-muted d-block">Signed in as</small>
                    <strong>{{ Auth::user()->email }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- STUDENT STATS --}}
    @if(Auth::user()->role === 'student')
    <div class="row g-3 fade-in-up">
        <div class="col-md-3">
            <div class="card stat-card bg-primary-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title bold-course">My Courses</h5>
                        <h2>{{ Auth::user()->student->enrolledCourses()->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-success-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Completed</h5>
                        <h2>{{ Auth::user()->student->completedCourses()->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-info-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Available</h5>
                        <h2>{{ App\Models\Course::where('status', 'active')->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-plus-circle" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-warning-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Credits</h5>
                        <h2>{{ Auth::user()->student->enrolledCourses()->sum('credits') }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-star" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- LECTURER STATS --}}
    @if(Auth::user()->role === 'lecturer')
    <div class="row g-3 fade-in-up">
        <div class="col-md-3">
            <div class="card stat-card bg-primary-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">My Courses</h5>
                        <h2>{{ Auth::user()->lecturer->courses()->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-success-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Students</h5>
                        <h2>{{ Auth::user()->lecturer->courses()->withCount('students')->get()->sum('students_count') }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ADMIN STATS --}}
    @if(Auth::user()->role === 'admin')
    <div class="row g-3 fade-in-up">
        <div class="col-md-3">
            <div class="card stat-card bg-primary-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <h2>{{ \App\Models\User::count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-success-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Courses</h5>
                        <h2>{{ \App\Models\Course::count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-info-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Students</h5>
                        <h2>{{ \App\Models\Student::count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-badge" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-warning-gradient text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Lecturers</h5>
                        <h2>{{ \App\Models\Lecturer::count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-workspace" style="font-size:2.1rem; opacity:0.95;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MENU (tab locations preserved) --}}
    <div class="row mt-4 mb-3 fade-in-up">
        <div class="col-12">
            <div class="card system-card menu-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 menu-title">Menu</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @if(Auth::user()->role === 'student')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('enrollments.available') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-search"></i>
                                <span>Browse Courses</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('enrollments.my-courses') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-list-ul"></i>
                                <span>My Courses</span>
                            </a>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'lecturer')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('courses.index') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-gear"></i>
                                <span>Manage Courses</span>
                            </a>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('admin.users.index') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-people"></i>
                                <span>Manage Users</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('courses.index') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-journal-text"></i>
                                <span>Manage Courses</span>
                            </a>
                        </div>
                        @endif

                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('profile.show') }}" class="menu-btn menu-accent w-100 text-decoration-none" role="button" data-wave>
                                <i class="bi bi-person-circle"></i>
                                <span>My Profile</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Main content column --}}
        <div class="col-md-8">
            @if(Auth::user()->role === 'student')
            <div class="card system-card enroll-card fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0 enroll-title">My Current Enrollments</h5>
                </div>
                <div class="card-body">
                    @if(Auth::user()->student->enrolledCourses()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Code</th>
                                        <th>Credits</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->student->enrolledCourses()->take(5)->get() as $course)
                                    <tr>
                                        <td>{{ $course->name }}</td>
                                        <td><span class="badge bg-white text-muted">{{ $course->code }}</span></td>
                                        <td>{{ $course->credits }}</td>
                                        <td><span class="badge badge-status bg-primary text-white">Enrolled</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('enrollments.my-courses') }}" class="btn btn-primary">View All My Courses</a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book" style="font-size: 3rem; color: rgba(11,37,69,0.18);"></i>
                            <h6 class="mt-3 text-muted">No courses enrolled yet</h6>
                            <a href="{{ route('enrollments.available') }}" class="btn btn-primary mt-2" data-wave>Browse Available Courses</a>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if(Auth::user()->role === 'lecturer')
            <div class="card system-card enroll-card fade-in-up">
                <div class="card-header">
                    <h5 class="mb-0 enroll-title">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No recent activities to show.</p>
                </div>
            </div>
            @endif

            @if(Auth::user()->role === 'admin')
            <div class="row g-3">
                {{-- Recent Activities --}}
                <div class="col-md-7">
                    <div class="card system-card enroll-card fade-in-up">
                        <div class="card-header">
                            <h5 class="mb-0 enroll-title">Recent Activities</h5>
                        </div>
                        <div class="card-body">

                            {{-- Determine recent activities: prefer injected $recentActivities, else try Activity model, else fallback to static sample --}}
                            @php
                                if (!isset($recentActivities)) {
                                    if (class_exists('App\\Models\\Activity')) {
                                        try {
                                            $recentActivities = \App\Models\Activity::latest()->take(3)->get();
                                        } catch (Throwable $e) {
                                            $recentActivities = collect();
                                        }
                                    } else {
                                        $recentActivities = collect([
                                            (object)[ 'icon' => 'bi-person-plus-fill', 'text' => 'New student account created', 'time' => '2 hours ago', 'type' => 'primary' ],
                                            (object)[ 'icon' => 'bi-journal-check', 'text' => 'Advanced Database Systems published', 'time' => '5 hours ago', 'type' => 'success' ],
                                            (object)[ 'icon' => 'bi-pencil-square', 'text' => 'Lecturer profile updated', 'time' => '1 day ago', 'type' => 'info' ],
                                        ]);
                                    }
                                }

                                // Ensure maximum 3 items
                                $recentActivities = $recentActivities->take(3);
                            @endphp

                            @if($recentActivities->count())
                                <ul class="activity-list mb-0">
                                    @foreach($recentActivities as $act)
                                    <li>
                                        @php
                                            // if $act is a model, try to extract fields; otherwise assume object with properties
                                            $icon = property_exists($act, 'icon') ? $act->icon : (data_get($act, 'icon') ?: 'bi-info-circle');
                                            $text = property_exists($act, 'text') ? $act->text : (data_get($act, 'description') ?: data_get($act, 'title', 'Activity'));
                                            $time = property_exists($act, 'time') ? $act->time : (data_get($act, 'created_at') ? \Carbon\Carbon::parse(data_get($act,'created_at'))->diffForHumans() : 'just now');
                                            $type = property_exists($act, 'type') ? $act->type : (data_get($act,'level') ?: 'info');
                                            $badgeClass = 'activity-badge-info';
                                            if(in_array($type, ['primary','info','success','warning'])) {
                                                $badgeClass = 'activity-badge-'. $type;
                                            }
                                        @endphp

                                        <span class="activity-icon-badge {{ $badgeClass }}">
                                            <i class="{{ $icon }}"></i>
                                        </span>
                                        <div class="activity-content">
                                            <span class="activity-text">{{ $text }}</span>
                                            <span class="activity-time">{{ $time }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">No recent activities to show.</p>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- System Information --}}
                <div class="col-md-5 system-info-column">
                    <div class="card system-card system-info-card fade-in-up">
                        <div class="card-header">
                            <h5 class="mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Account:</strong> {{ Auth::user()->email }}</li>
                                <li class="mb-2"><strong>Member Since:</strong> {{ Auth::user()->created_at->format('M Y') }}</li>
                                <li class="mb-2"><strong>Last Login:</strong> {{ now()->format('M d, Y') }}</li>
                            </ul>

                            <hr>

                            <h6>Recent Announcements</h6>
                            <div class="alert alert-info">
                                <small><strong>Course Registration:</strong> FRS is open now!</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right column for Student and Lecturer --}}
        @if(Auth::user()->role === 'student' || Auth::user()->role === 'lecturer')
        <div class="col-md-4">
            <div class="card system-card system-info-card fade-in-up">
                <div class="card-header"><h5 class="mb-0">System Information</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Account:</strong> {{ Auth::user()->email }}</li>
                        <li class="mb-2"><strong>Member Since:</strong> {{ Auth::user()->created_at->format('M Y') }}</li>
                        <li class="mb-2"><strong>Last Login:</strong> {{ now()->format('M d, Y') }}</li>
                    </ul>

                    <hr>

                    <h6>Recent Announcements</h6>
                    <div class="alert alert-info">
                        <small><strong>Course Registration:</strong> FRS is open now!</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>

<!-- JS: keep waves, menu accessibility, wheel tweaks; stardust removal done -->
<script>
    // Respect reduced motion
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Create periodic gentle waves at random positions (background ambience)
    (function createAutoWaves(){
        if(reduceMotion) return;
        const layer = document.getElementById('waveLayer');
        if(!layer) return;
        const spawn = () => {
            const wave = document.createElement('div');
            wave.className = 'wave';
            // random position within central area (avoid extremes)
            const x = 20 + Math.random()*60; // percent
            const y = 10 + Math.random()*70;
            wave.style.left = x + '%';
            wave.style.top = y + '%';
            layer.appendChild(wave);
            // remove after animation
            setTimeout(()=> wave.remove(), 2600);
        };
        // spawn a soft wave every 6-10s staggered
        const schedule = () => setTimeout(()=>{ spawn(); schedule(); }, 6000 + Math.random()*4000 );
        schedule();
    })();

    // Add wave on buttons when hovered/clicked
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
        // convert page coords to percentage relative to viewport
        const x = (pageX / window.innerWidth) * 100;
        const y = (pageY / window.innerHeight) * 100;
        wave.style.left = x + '%';
        wave.style.top = y + '%';
        layer.appendChild(wave);
        setTimeout(()=> wave.remove(), 2400);
    }

    // subtle animation speed randomization for wheels
    document.querySelectorAll('.bg-wheel').forEach((wheel, idx) => {
        const base = 100 + Math.random()*60;
        wheel.style.animationDuration = base + 's';
        if(Math.random() < 0.5) wheel.style.animationDirection = 'reverse';
    });

    // keyboard accessibility for menu buttons
    document.querySelectorAll('.menu-btn').forEach(btn => {
        btn.addEventListener('keydown', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.add('active'); });
        btn.addEventListener('keyup', (e) => { if(e.key === 'Enter' || e.key === ' ') btn.classList.remove('active'); });
    });

</script>

@endsection
