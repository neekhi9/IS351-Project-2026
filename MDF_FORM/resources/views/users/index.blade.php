@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Users') }}</div>

                <div class="card-body">
                      @session('success')
                <div class="container">
                    <div class="alert alert-success">
                        {{ $value }}
                    </div>
                </div>
            @endsession

                    <a href="{{route('users.create')}}" class="btn btn-success mb-3">Create User</a>

                  <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user-> id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>  
                                <td>@foreach($user->getRoleNames() as $role)
                                    <button class="btn btn-success btn-sm">{{ $role }}</button>
                                    @endforeach
                                </td> 
                                <td>
                                   <form method="POST" action="{{route('users.destroy', $user->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    @can('user-list')
                                    <a href="{{route('users.show', $user->id)}}" class="btn btn-info btn-sm">Show</a>
                                    @endcan
                                     @can('user-edit')
                                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                    @endcan
                                     @can('user-delete')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                    @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
