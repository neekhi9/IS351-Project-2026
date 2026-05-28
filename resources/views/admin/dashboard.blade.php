@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3"><h5>Total Users</h5><p class="fs-4 mb-0">{{ $stats['users'] }}</p></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Menu Items</h5><p class="fs-4 mb-0">{{ $stats['menus'] }}</p></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Total Orders</h5><p class="fs-4 mb-0">{{ $stats['orders'] }}</p></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Pending Orders</h5><p class="fs-4 mb-0">{{ $stats['pending_orders'] }}</p></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Total Reservations</h5><p class="fs-4 mb-0">{{ $stats['reservations'] }}</p></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Pending Reservations</h5><p class="fs-4 mb-0">{{ $stats['pending_reservations'] }}</p></div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.menu.index') }}" class="btn btn-primary">Manage Menu</a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Manage Users</a>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-dark">Manage Roles</a>
        <a href="{{ route('kitchen.index') }}" class="btn btn-success">Kitchen Dashboard</a>
    </div>
</div>
@endsection
