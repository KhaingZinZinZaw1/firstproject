@extends('users.layouts.app')

@section('title', 'Users List')

@section('content')
<div class="bg-light py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Users List</h3>
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm me-2">Create User</a>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="alert alert-success py-1 px-2">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-1 px-2">{{ $errors->first() }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Img</th>
                        <th>Role</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>
                            @if($user->img)
                                <img src="{{ asset('storage/' . $user->img) }}" alt="Profile" width="40" class="rounded-circle">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($user->role == 1) Admin
                            @elseif($user->role == 2) Member
                            @else Default
                            @endif
                        </td>
                        <td>{{ $user->created_by ?? '-' }}</td>
                        <td>{{ $user->updated_by ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm mb-1">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
