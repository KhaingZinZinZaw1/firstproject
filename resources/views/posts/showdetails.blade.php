@extends('users.layouts.app')

@section('title', $post->title)

@section('content')
<!-- Read More view -->
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-dark mb-3">{{ $post->title }}</h2>
            <p class="card-text text-dark">{{ $post->description }}</p>
        </div>
    </div>
        <hr>
        <h5>Comments ({{ $post->comments->count() }})</h5>

        @foreach($post->comments as $comment)
            <div class="mb-2">
                <strong>{{ $comment->user->name }}</strong>
                <div>{{ $comment->comment}}</div>
                <small class="text-muted">{{ $comment->created_at->diffForHumans()}}</small>
            </div>
        @endforeach

        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                @csrf
                <textarea name="comment" class="form-control" rows="3" required>{{ old('comment') }}</textarea>
                @error('comment')<div class="text-danger small">{{ $message }}</div>@enderror
                <button class="btn btn-success btn-sm mt-2">Post Comment</button>
            </form>
        @else
            <p class="mt-2"><a href="{{ route('login') }}">Login</a> to leave a comment.</p>
        @endauth

    <!-- Back Button -->
    @if(auth()->check())
        <a href="{{ route('users.show', auth()->id()) }}" class="btn btn-secondary mt-3">Back</a>
    @else
        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Back to Home</a>
    @endif
</div>
<!-- Read More view -->
@endsection
