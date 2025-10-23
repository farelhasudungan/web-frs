@extends('layouts.app')

@section('title', 'Login - Course System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4 mt-5">
            <div class="card-header bg-primary text-white text-center rounded-top-4">
                <h4 class="mb-0">Login to Course System</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="Enter your email" 
                            required 
                            autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter your password" 
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="remember" 
                            name="remember" 
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="form-check-label">Remember Me</label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            Login
                        </button>
                    </div>
                </form>

                {{-- Register & Forgot Password Links --}}
                <div class="text-center mt-4">
                    <p class="mb-1">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Register here</a>
                    </p>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">
                            Forgot your password?
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
