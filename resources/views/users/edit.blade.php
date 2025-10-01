@extends('users.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Edit User</h3>

        <!-- Success/Error Messages -->
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $user->name) }}" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $user->email) }}" 
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror"
                >
                <small class="text-muted">Leave blank if you don’t want to change password.</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-control"
                >
            </div>

            <!-- Profile Picture -->
            <div class="mb-3">
                <label for="img" class="form-label">Profile Picture</label>
                <input 
                    type="file" 
                    name="img" 
                    id="img" 
                    class="form-control @error('img') is-invalid @enderror" 
                    accept="image/*"
                >
                @error('img')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select 
                    name="role" 
                    id="role" 
                    class="form-select @error('role') is-invalid @enderror" 
                    required
                >
                    <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Member</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">Update User</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('users.list') }}" class="text-decoration-none">Back to Users List</a>
        </div>
    </div>
</div>
@endsection
