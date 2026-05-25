@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Dashboard') }}</h5>
                    <span class="badge bg-light text-dark">Welcome Back</span>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-3">Hello, {{ Auth::user()->name }} 👋</h4>
                    <p class="text-muted">You are successfully logged in to your account.</p>

                    <hr>

                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h6 class="fw-bold">Profile</h6>
                                <p class="text-muted mb-2">Manage your account details.</p>
                                 <a href="{{ route('users.edit', Auth::user()->id) }}" class="btn btn-sm btn-success">Settings</a>

                               
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h6 class="fw-bold">Settings</h6>
                                <p class="text-muted mb-2">Customize your preferences.</p>
                              
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h6 class="fw-bold">Support</h6>
                                <p class="text-muted mb-2">Need help? Get assistance.</p>
                              
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
