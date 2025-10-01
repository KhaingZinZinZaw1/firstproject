@extends('users.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="bg-light py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">User Details</h3>
            <a href="{{ route('users.list') }}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="alert alert-success py-1 px-2">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-1 px-2">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm p-4 mb-4">
            <div class="text-center mb-3">
                @if($user->img)
                    <img src="{{ asset('storage/' . $user->img) }}" alt="Profile" width="100" class="rounded-circle">
                @else
                    <span class="badge bg-secondary fs-5">No Image</span>
                @endif
            </div>

            <table class="table table-bordered table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>
                        @if($user->role == 1) Admin
                        @elseif($user->role == 2) Member
                        @else Default
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $user->created_by ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td>{{ $user->updated_by ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $user->updated_at->format('d-m-Y H:i') }}</td>
                </tr>
            </table>

            <div class="mt-3 text-center">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary me-2">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
