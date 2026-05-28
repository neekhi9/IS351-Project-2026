@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Place Order</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="card p-4">
        @csrf

        <div id="order-items">
            <div class="row g-2 mb-3 order-row">
                <div class="col-md-6">
                    <label class="form-label">Menu Item</label>
                    <select name="items[0][menu_id]" class="form-select" required>
                        <option value="">Select item</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->item_name }} - ${{ number_format($menu->price, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                </div>
            </div>
        </div>

        <button type="button" id="add-item" class="btn btn-outline-secondary mb-3">Add Another Item</button>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Order</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let index = 1;
    const addBtn = document.getElementById('add-item');
    const container = document.getElementById('order-items');
    const options = `@foreach($menus as $menu)<option value="{{ $menu->id }}">{{ $menu->item_name }} - ${{ number_format($menu->price, 2) }}</option>@endforeach`;

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-3 order-row';
        row.innerHTML = `
            <div class="col-md-6">
                <label class="form-label">Menu Item</label>
                <select name="items[${index}][menu_id]" class="form-select" required>
                    <option value="">Select item</option>
                    ${options}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="items[${index}][quantity]" class="form-control" min="1" value="1" required>
            </div>
        `;
        container.appendChild(row);
        index++;
    });
});
</script>
@endsection
