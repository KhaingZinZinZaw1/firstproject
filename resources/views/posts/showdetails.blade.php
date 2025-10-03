@extends('users.layouts.app')

@section('title', $post->title)

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-dark mb-3">{{ $post->title }}</h2>
            <p class="card-text text-dark">{{ $post->description }}</p>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">Back to Home</a>
    </div>
</div>
@endsection
