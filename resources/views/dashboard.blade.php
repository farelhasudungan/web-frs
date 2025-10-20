@extends('layouts.app')

@section('title', 'Dashboard - Course System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Welcome, {{ Auth::user()->student->student_name }}!</h1>
            <small class="text-muted">Course Registration System Dashboard</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">My Courses</h5>
                        <h2>{{ Auth::user()->student->enrolledCourses()->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Completed</h5>
                        <h2>{{ Auth::user()->student->completedCourses()->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Available</h5>
                        <h2>{{ App\Models\Course::where('status', 'active')->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-plus-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Credits</h5>
                        <h2>{{ Auth::user()->student->enrolledCourses()->sum('credits') }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-star" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Menu</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('enrollments.available') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-search"></i> Browse Courses
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('enrollments.my-courses') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-list-ul"></i> My Courses
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-gear"></i> Manage Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Current Enrollments</h5>
            </div>
            <div class="card-body">
                @if(Auth::user()->student->enrolledCourses()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th>Credits</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(Auth::user()->student->enrolledCourses()->take(5)->get() as $course)
                                <tr>
                                    <td>{{ $course->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $course->code }}</span></td>
                                    <td>{{ $course->credits }}</td>
                                    <td>
                                        <span class="badge bg-primary">Enrolled</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('enrollments.my-courses') }}" class="btn btn-primary">View All My Courses</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-book" style="font-size: 3rem; color: #ccc;"></i>
                        <h6 class="mt-2 text-muted">No courses enrolled yet</h6>
                        <a href="{{ route('enrollments.available') }}" class="btn btn-primary">Browse Available Courses</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Account:</strong> {{ Auth::user()->student->email }}
                    </li>
                    <li class="mb-2">
                        <strong>Member Since:</strong> {{ Auth::user()->student->created_at->format('M Y') }}
                    </li>
                    <li class="mb-2">
                        <strong>Last Login:</strong> {{ now()->format('M d, Y') }}
                    </li>
                </ul>
                
                <hr>
                
                <h6>Recent Announcements</h6>
                <div class="alert alert-info">
                    <small>
                        <strong>Course Registration:</strong> FRS is open now!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
