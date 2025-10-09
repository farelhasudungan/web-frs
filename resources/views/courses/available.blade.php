@extends('layouts.app')

@section('content')
<h1>Available Courses</h1>

<div class="row">
    @foreach($courses as $course)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $course->code }} - {{ $course->name }}</h5>
                <p class="card-text">{{ $course->description }}</p>
                <p>
                    <strong>Credits:</strong> {{ $course->credits }}<br>
                    <strong>Available Slots:</strong> {{ $course->max_students - $course->enrolled_count }}/{{ $course->max_students }}
                </p>
                
                @if($course->prerequisites->count() > 0)
                <p><strong>Prerequisites:</strong> {{ $course->prerequisites->pluck('code')->implode(', ') }}</p>
                @endif

                @if($course->can_enroll)
                    <form action="{{ route('enrollments.enroll', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Enroll</button>
                    </form>
                @else
                    <div class="alert alert-warning mb-0">
                        Cannot enroll: {{ implode(', ', $course->enrollment_errors) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection