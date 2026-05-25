@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Show Roles') }}</div>

                <div class="card-body">

                    <a href="{{route('roles.index')}}" class="btn btn-info mb-3"> Back</a> 
                    <p><strong>Name: </strong>{{$role->name}}</p>   
                
                    <p><strong>Permissions: </strong></p>
                    @foreach ($role->permissions as $permission)
                    <li class="ms-3">{{ $permission->name }}</li>
                    @endforeach
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
