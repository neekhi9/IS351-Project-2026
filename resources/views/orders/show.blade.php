@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Order #{{ $order->id }}</h2>

    <div class="card p-3 mb-3">
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Ordered At:</strong> {{ optional($order->ordered_at)->format('Y-m-d H:i') }}</p>
        <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?: 'N/A' }}</p>
    </div>

    <h4>Items</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Menu Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->menu->item_name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
