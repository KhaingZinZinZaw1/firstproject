@extends('users.layouts.app')

@section('title', 'Member Dashboard')

@section('content')
<div class="bg-light py-4">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">My Dashboard</h3>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
            </form>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="alert alert-success py-1 px-2">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-1 px-2">{{ $errors->first() }}</div>
        @endif

        {{-- Member Info --}}
        <div class="card shadow-sm p-4 mb-4">
            <div class="text-center mb-3">
                @if($user->img)
                    <img src="{{ asset('storage/' . $user->img) }}" alt="Profile" width="100" class="rounded-circle">
                @else
                    <span class="badge bg-secondary fs-5">No Image</span>
                @endif
            </div>

            <table class="table table-bordered table-sm">
                <tr><th>Name</th><td>{{ $user->name }}</td></tr>
                <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                <tr><th>Role</th><td>{{ $user->role == 2 ? 'Member' : 'Default' }}</td></tr>
                <tr><th>Created At</th><td>{{ $user->created_at->format('d-m-Y H:i') }}</td></tr>
            </table>

            <div class="mt-3 text-center">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary me-2">Edit My Info</a>
            </div>
        </div>

        {{-- Member's Own Posts --}}
        <h4 class="mb-3">My Posts</h4>
        <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Create New Post</a>

        @if($myPosts->count() > 0)
            <div class="row">
                @foreach($myPosts as $post)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- {{ $myPosts->links() }} -->
        @else
            <p>No posts yet. <a href="{{ route('posts.create') }}">Create one</a></p>
        @endif

        {{-- Public Posts --}}
        <h4 class="mt-5 mb-3">Other Posts</h4>
        @if($allPosts->count() > 0)
            <div class="row">
                @foreach($allPosts as $post)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit($post->description, 100) }}</p>
                                <a href="{{ route('posts.showdetails', $post->id) }}" class="btn btn-success btn-sm">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $allPosts->links('pagination::bootstrap-5') }}
        @else
            <p>No public posts available.</p>
        @endif

    </div>
</div>
@endsection
