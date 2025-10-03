@extends('users.layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="width: 500px;">
        <h3 class="text-center mb-4">Edit Post</h3>

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" 
                    class="form-control @error('title') is-invalid @enderror" 
                    value="{{ old('title', $post->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" 
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Visibility (Radio Buttons) -->
            <div class="mb-3">
                <label class="form-label d-block">Visibility</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('public_flag') is-invalid @enderror" 
                        type="radio" name="public_flag" id="public" value="1" 
                        {{ old('public_flag', $post->public_flag) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="public">Public</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('public_flag') is-invalid @enderror" 
                        type="radio" name="public_flag" id="private" value="0" 
                        {{ old('public_flag', $post->public_flag) == 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="private">Private</label>
                </div>
                @error('public_flag')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Post</button>
        </form>
    </div>
</div>
@endsection
