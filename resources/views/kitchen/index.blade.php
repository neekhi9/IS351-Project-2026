@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Kitchen Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Ordered At</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Items</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ optional($order->ordered_at)->format('Y-m-d H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($order->status) }}</span></td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($order->items as $item)
                                    <li>{{ $item->menu->item_name ?? 'N/A' }} x {{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <form action="{{ route('kitchen.orders.updateStatus', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm mb-2" required>
                                    @foreach(['pending','preparing','ready','completed','cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No kitchen orders available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
