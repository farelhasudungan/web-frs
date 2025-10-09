@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Course</h1>
    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">View Course</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Course Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('courses.update', $course) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
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
                                  id="description" name="description" rows="3">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="credits" class="form-label">Credits</label>
                            <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                                   id="credits" name="credits" value="{{ old('credits', $course->credits) }}" 
                                   min="1" max="6" required>
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_students" class="form-label">Maximum Students</label>
                            <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                   id="max_students" name="max_students" 
                                   value="{{ old('max_students', $course->max_students) }}" min="1" required>
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
                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Course Info</h5>
            </div>
            <div class="card-body">
                <dl class="row">
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
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Current Prerequisites</h5>
            </div>
            <div class="card-body">
                @foreach($course->prerequisites as $prerequisite)
                    <span class="badge bg-info me-2 mb-1">
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
@endsection