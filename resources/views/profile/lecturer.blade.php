@extends('layouts.app')

@section('title', 'Lecturer Profile - Course System')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Lecturer Profile</h1>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Name: {{ Auth::user()->lecturer->lecturer_name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ Auth::user()->lecturer->phone }}</p>
                <p class="card-text"><strong>Department:</strong> {{ Auth::user()->lecturer->department }}</p>
                <p class="card-text"><strong>Laboratorium:</strong> {{ Auth::user()->lecturer->laboratorium }}</p>
                <p class="card-text"><strong>Address:</strong> {{ Auth::user()->lecturer->address }}</p>
                <a href="{{ route('profile.setup') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection