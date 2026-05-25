@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Show Users') }}</div>

                <div class="card-body">

                    <a href="{{ route('users.index') }}" class="btn btn-info mb-3">Back</a>
                    <div class="d-flex align-items-center gap-4">
                        <img src="{{ $user->profile_photo_url }}" class="rounded-circle border" width="96" height="96" alt="Avatar">
                        <div>
                            <p class="mb-1"><strong>Name: </strong>{{ $user->name }}</p>
                            <p class="mb-0"><strong>Email: </strong>{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
