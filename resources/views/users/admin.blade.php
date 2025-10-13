@extends('users.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Admin Dashboard</h2>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
        </form>
    </div>
    
    <form action="{{ route('users.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" class="btn btn-primary">Upload CSV</button>
    </form>

    <a href="{{ route('users.download') }}" class="btn btn-success">Download CSV</a>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- USERS SECTION --}}
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Users List</h4>
            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">Create User</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered table-hover mb-0 text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
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
                            @if($user->role == 1) Admin
                            @elseif($user->role == 2) Member
                            @else Default
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <a href="{{ route('users.showuserdetail', $user->id) }}" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- POSTS SECTION --}}

    <form action="{{ route('posts.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" class="btn btn-primary">Upload CSV</button>
    </form>
    <a href="{{ route('posts.download') }}" class="btn btn-success">Download CSV</a>
    
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Posts List</h4>
            <a href="{{ route('posts.create') }}" class="btn btn-light btn-sm">Create Post</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered table-hover mb-0 text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Visibility</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->description ?? '-' }}</td>
                        <td>
                            @if($post->public_flag)
                                <span class="badge bg-success">Public</span>
                            @else
                                <span class="badge bg-secondary">Private</span>
                            @endif
                        </td>
                        <td>{{ $post->created_by ?? '-' }}</td>
                        <td>{{ $post->updated_by ?? '-' }}</td>
                        <td>{{ $post->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $post->updated_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm mb-1">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm mb-1">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
