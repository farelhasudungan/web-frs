@extends('layouts.app')

@section('content')
<h1>Create New Course</h1>

<form method="POST" action="{{ route('courses.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="code" class="form-label">Course Code</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                   id="code" name="code" value="{{ old('code') }}" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Course Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" 
                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="credits" class="form-label">Credits</label>
            <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                   id="credits" name="credits" value="{{ old('credits', 3) }}" min="1" max="6" required>
            @error('credits')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="max_students" class="form-label">Maximum Students</label>
            <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                   id="max_students" name="max_students" value="{{ old('max_students', 30) }}" min="1" required>
            @error('max_students')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="prerequisites" class="form-label">Prerequisites</label>
        <select class="form-select" id="prerequisites" name="prerequisites[]" multiple>
            @foreach($courses as $prereq)
                <option value="{{ $prereq->id }}">{{ $prereq->code }} - {{ $prereq->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple prerequisites</small>
    </div>

    <button type="submit" class="btn btn-primary">Create Course</button>
    <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection