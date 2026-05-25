@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">My Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body d-flex align-items-center gap-4">
            <img src="{{ $user->profile_photo_url }}" class="rounded-circle border" width="96" height="96" alt="Avatar">
            <div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <div class="text-muted">{{ $user->email }}</div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Update Profile</div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <button type="submit" class="btn btn-success">Update Name</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Update Profile Picture</div>
        <div class="card-body">
            <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Choose an image (jpg, jpeg, png, webp) up to 2MB</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
