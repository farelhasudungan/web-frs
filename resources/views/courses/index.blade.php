@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Courses</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">Add New Course</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Credits</th>
                <th>Enrolled/Max</th>
                <th>Prerequisites</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->code }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->credits }}</td>
                <td>{{ $course->enrolled_count }}/{{ $course->max_students }}</td>
                <td>
                    @if($course->prerequisites->count() > 0)
                        {{ $course->prerequisites->pluck('code')->implode(', ') }}
                    @else
                        None
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($course->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $courses->links() }}
@endsection