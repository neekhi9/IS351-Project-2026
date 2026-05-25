@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Update Users') }}</div>

                <div class="card-body">

                    <a href="{{route('users.index')}}" class="btn btn-info mb-3"> Back</a>

                    
                    <form method="POST" action="{{ route('users.update',$user->id) }}" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <input type="email" name="email" placeholder="Email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Current Avatar:</strong>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->profile_photo_url }}" class="rounded-circle border" width="72" height="72" alt="Avatar">
                                        <span class="text-muted">Upload a new image to replace</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Profile Photo:</strong>
                                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Confirm Password:</strong>
                                    <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                                </div>
                            </div> -->
                            @can('user-edit')
                            <div class="mb-3">
                                <h4 class="mt-3">Assign Roles</h4>
                                    <select name="role[]" class="form-select" multiple>
                                        
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? "selected" : ""}}>{{ $role->name }}</option>
                                    @endforeach
                                
                                    </select>
                               </div>
                                   @endcan
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                            </div>
                        </div>
                    </form>

                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
