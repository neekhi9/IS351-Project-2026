@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Menu</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    @if(auth()->user()->hasRole('admin'))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                    <tr>
                        <td>{{ $menu->item_name }}</td>
                        <td>{{ $menu->category }}</td>
                        <td>{{ $menu->description }}</td>
                        <td>${{ number_format($menu->price, 2) }}</td>
                        <td>
                            @if($menu->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </td>
                        @if(auth()->user()->hasRole('admin'))
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')" type="submit">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No menu items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
