@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Menu Item</h2>

    <form action="{{ route('admin.menu.update', $menu) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $menu->item_name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $menu->category) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $menu->price) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $menu->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="is_available" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_available">Available</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
