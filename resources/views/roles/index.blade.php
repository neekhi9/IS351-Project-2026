@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header .bg-primary">{{ __('Roles') }}</div>

                <div class="card-body">
                      @session('success')
                <div class="container">
                    <div class="alert alert-success">
                        {{ $value }}
                    </div>
                </div>
            @endsession

                    <a href="{{route('roles.create')}}" class="btn btn-success mb-3">Create Role</a>

                  <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <!-- <th>Email</th> -->
                            <!-- <th>Roles</th> -->
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role-> id }}</td>
                                <td>{{ $role->name }}</td>
                                <!-- <td>{{ $role->email }}</td>    -->
                                <td>
                                   <form method="POST" action="{{route('roles.destroy', $role->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    @can('role-list')
                                    <a href="{{route('roles.show', $role->id)}}" class="btn btn-info btn-sm">Show</a>
                                    @endcan
                                     @can('role-edit')
                                    <a href="{{route('roles.edit', $role->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                    @endcan
                                     @can('role-delete')
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
