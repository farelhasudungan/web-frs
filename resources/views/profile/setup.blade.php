@extends('layouts.app')

@section('title', 'Profile Setup - Course System')

// I want to make edit/update user data based on their role, so make a check role first

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Profile Setup</h1>
        <div class="card mt-4">
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        @if(Auth::user()->role == 'student')
                            <label for="student_name" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="student_name" name="student_name" value="{{ Auth::user()->student->student_name }}" required>
                        @elseif(Auth::user()->role == 'lecturer')
                            <label for="lecturer_name" class="form-label">Lecturer Name</label>
                            <input type="text" class="form-control" id="lecturer_name" name="lecturer_name" value="{{ Auth::user()->lecturer->lecturer_name }}" required>
                        @elseif(Auth::user()->role == 'admin')
                            <label for="admin_name" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" value="{{ Auth::user()->admin->admin_name }}" required>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required>
                            {{  Auth::user()->role == 'student' ? Auth::user()->student->address :
                                (Auth::user()->role == 'lecturer' ? Auth::user()->lecturer->address :
                                (Auth::user()->role == 'admin' ? Auth::user()->admin->address : ''))
                            }}
                        </textarea>
                   </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div> 
        </div>
    </div>
</div>
@endsection