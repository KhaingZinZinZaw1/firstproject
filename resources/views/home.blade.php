@extends('users.layouts.app')

@section('title', 'Home Page')

@section('content')
<div class="container mt-5">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5 p-4 rounded shadow-sm" style="background-color: #20c997; color: white;">
        <h1 class="display-4">Welcome!!!</h1>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login</a>
    </div>

    <h2 class="mb-4 text-secondary">Public Posts</h2>

    @if($posts->count() > 0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column bg-light">
                            <h5 class="card-title text-dark">{{ $post->title }}</h5>
                            <p class="card-text flex-grow-1 text-muted">{{ Str::limit($post->description, 120) }}</p>
                            <a href="{{ route('posts.showdetails', $post->id) }}" class="btn btn-success mt-auto">Read More</a>
                        </div>
                        <div class="card-footer bg-white text-muted border-top-0">
                            Posted on {{ $post->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    @else
        <div class="alert alert-info">No public posts available.</div>
    @endif
</div>
@endsection