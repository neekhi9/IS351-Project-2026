@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Customer Dashboard</h2>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Browse Menu</h5>
                <p>View available dishes and prices.</p>
                <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">View Menu</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Place Order</h5>
                <p>Create a new food order.</p>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">Order Now</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Reserve Table</h5>
                <p>Book your table for dine-in.</p>
                <a href="{{ route('reservations.create') }}" class="btn btn-success">Make Reservation</a>
            </div>
        </div>
    </div>
</div>
@endsection
