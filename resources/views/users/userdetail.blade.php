@extends('users.layouts.app')

@section('title', 'User Detail')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">User Detail</h3>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong>Name:</strong></div>
                <div class="col-md-9">{{ $user->name }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Email:</strong></div>
                <div class="col-md-9">{{ $user->email }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Role:</strong></div>
                <div class="col-md-9">
                    @if($user->role == 1) Admin
                    @elseif($user->role == 2) Member
                    @else Default
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Joined At:</strong></div>
                <div class="col-md-9">{{ $user->created_at->format('d-m-Y H:i') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Created By:</strong></div>
                <div class="col-md-9">{{ $user->created_by ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong>Updated By:</strong></div>
                <div class="col-md-9">{{ $user->updated_by ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
