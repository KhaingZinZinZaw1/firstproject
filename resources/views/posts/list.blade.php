@extends('users.layouts.app')

@section('title', 'Posts List')

@section('content')
<div class="bg-light py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Posts List</h3>
            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">Create Post</a>
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
