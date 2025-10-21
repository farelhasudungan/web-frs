@extends('layouts.app')

@section('title', 'Admin Profile - Course System')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Admin Profile</h1>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Name: {{ Auth::user()->admin->admin_name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ Auth::user()->admin->phone }}</p>
                <p class="card-text"><strong>Department:</strong> {{ Auth::user()->admin->department }}</p>
                <a href="{{ route('profile.setup') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection