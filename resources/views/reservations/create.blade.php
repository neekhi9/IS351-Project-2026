@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Book a Table</h2>

    <form action="{{ route('reservations.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Reservation Date & Time</label>
            <input type="datetime-local" name="reservation_time" class="form-control" value="{{ old('reservation_time') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Number of Guests</label>
            <input type="number" min="1" max="20" name="guest_count" class="form-control" value="{{ old('guest_count', 2) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Special Request</label>
            <textarea name="special_request" class="form-control" rows="3">{{ old('special_request') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Confirm Reservation</button>
    </form>
</div>
@endsection
