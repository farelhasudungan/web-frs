@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Course Details</h1>
    <div>
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">Edit Course</a>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to Courses</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">{{ $course->code }} - {{ $course->name }}</h3>
            </div>
            <div class="card-body">
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

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
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

        <div class="card mt-3">
            <div class="card-header">
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
@endsection