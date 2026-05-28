@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>My Reservations</h2>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Book Table</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date & Time</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>Special Request</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id }}</td>
                        <td>{{ optional($reservation->reservation_time)->format('Y-m-d H:i') }}</td>
                        <td>{{ $reservation->guest_count }}</td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst($reservation->status) }}</span></td>
                        <td>{{ $reservation->special_request ?: 'N/A' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No reservations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
