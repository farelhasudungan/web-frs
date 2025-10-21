@extends('layouts.app')

@section('title', 'Student Profile - Course System')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Student Profile</h1>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Name: {{ Auth::user()->student->student_name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ Auth::user()->student->phone }}</p>
                <p class="card-text"><strong>Date of Birth:</strong> {{ Auth::user()->student->date_of_birth }}</p>
                <p class="card-text"><strong>Address:</strong> {{ Auth::user()->student->address }}</p>
                <p class="card-text"><strong>Enrolled Courses:</strong> {{ Auth::user()->student->enrolledCourses()->count() }}</p>
                <a href="{{ route('profile.setup') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection