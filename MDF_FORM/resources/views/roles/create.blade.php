@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Create Role') }}</div>

                <div class="card-body">

                    <a href="{{route('roles.index')}}" class="btn btn-info mb-3"> Back</a>

                    
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Role:</strong>
                                    <input type="text" name="name" placeholder="name" class="form-control">
                                </div>
                            </div>
                            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <input type="email" name="email" placeholder="Email" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Password:</strong>
                                    <input type="password" name="password" placeholder="Password" class="form-control">
                                </div>
                            </div> -->
                            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Confirm Password:</strong>
                                    <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                                </div>
                            </div> -->
                           <div class="mb-3">
                                <h4 class="mt-3">Assign Permissions</h4>

                                    @foreach($permissions as $permission)
                                        <label>
                                            <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                            {{$permission->name}}</label></br>
                                    @endforeach
                            </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Create</button>
                                    </div>
                                    
                        </div>
                    </form>

                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
