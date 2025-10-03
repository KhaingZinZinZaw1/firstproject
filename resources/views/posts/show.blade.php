@extends('users.layouts.app')

@section('title', 'Post Details')

@section('content')
<div class="bg-light py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Post Details</h3>
            <a href="{{ route('posts.list') }}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>

        {{-- Alerts --}}
        @if(session('status'))
            <div class="alert alert-success py-1 px-2">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-1 px-2">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm p-4 mb-4">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $post->id }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $post->title }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $post->description ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Visibility</th>
                    <td>
                        @if($post->public_flag) Public @else Private @endif
                    </td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $post->created_by }}</td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td>{{ $post->updated_by ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $post->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $post->updated_at->format('d-m-Y H:i') }}</td>
                </tr>
            </table>

            <div class="mt-3 text-center">
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary me-2">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
