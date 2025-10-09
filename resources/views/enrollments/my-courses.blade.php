@extends('layouts.app')

@section('content')
<h1>My Courses</h1>

<h3>Currently Enrolled</h3>
@if($enrolledCourses->count() > 0)
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Credits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrolledCourses as $course)
            <tr>
                <td>{{ $course->code }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->credits }}</td>
                <td>
                    <form action="{{ route('enrollments.drop', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure you want to drop this course?')">Drop</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p>You are not currently enrolled in any courses.</p>
@endif

<h3 class="mt-4">Completed Courses</h3>
@if($completedCourses->count() > 0)
<div class="table-responsive">
    <table class="table">
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
<p>You have not completed any courses yet.</p>
@endif
@endsection