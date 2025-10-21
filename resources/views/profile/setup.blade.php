@extends('layouts.app')

@section('title', 'Profile Setup - Course System')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Complete Your {{ ucfirst($user->role) }} Profile</div>
                
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('profile.store') }}">
                        @csrf
                        
                        @if($user->role === 'student')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="student_name" 
                                       value="{{ optional($user->student)->student_name ?? $user->name }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" 
                                       value="{{ optional($user->student)->phone }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" 
                                       value="{{ optional($user->student)->date_of_birth }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ optional($user->student)->address }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Admission Year</label>
                                <input type="number" class="form-control" name="admission_year" 
                                       value="{{ optional($user->student)->admission_year ?? date('Y') }}"
                                       min="2000" max="{{ date('Y') + 1 }}" required>
                            </div>
                        @endif

                        @if($user->role === 'lecturer')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="lecturer_name" 
                                       value="{{ optional($user->lecturer)->lecturer_name ?? $user->name }}" required>
                            </div> 

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" 
                                       value="{{ optional($user->lecturer)->phone }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" 
                                       value="{{ optional($user->lecturer)->department }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Laboratorium</label>
                                <input type="text" class="form-control" name="laboratorium" 
                                       value="{{ optional($user->lecturer)->laboratorium }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ optional($user->lecturer)->address }}</textarea>
                            </div>
                        @endif

                        @if($user->role === 'admin')
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="admin_name" 
                                       value="{{ optional($user->admin)->admin_name ?? $user->name }}" required>
                            </div> 
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" 
                                       value="{{ optional($user->admin)->department }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ optional($user->admin)->address }}</textarea>
                            </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary">
                            Save Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection