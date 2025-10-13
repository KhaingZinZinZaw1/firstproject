@extends('users.layouts.app') 
@section('title', 'Login')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="w-100" style="max-width: 400px;">
        <div class="bg-white shadow-sm rounded p-4">

            <h2 class="text-center fw-bold mb-4">Login</h2>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert alert-success py-2 mb-3">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="form-control">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" required
                        class="form-control">
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center text-muted mt-3 mb-1">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register</a>
            </p>

            <p class="text-center mt-2 mb-0">
                <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">
                    Forgot Password?
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
