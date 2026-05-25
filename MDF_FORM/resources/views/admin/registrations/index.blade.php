@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard - Applications</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="row g-3 mb-3">
        <div class="col-auto">
            <label for="status" class="col-form-label">Filter by Status</label>
        </div>
        <div class="col-auto">
            <select name="status" id="status" class="form-select">
                <option value="">All</option>
                @php
                    $statuses = ['pending' => 'Pending', 'approved' => 'Approved', 'declined' => 'Declined', 'invalid' => 'Invalid'];
                @endphp
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Apply</button>
            <a class="btn btn-secondary" href="{{ route('admin.registrations.index') }}">Reset</a>
        </div>
    </form>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Type</th>
                    <th>Name / Organization</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr>
                        <td>#{{ $reg->id }}</td>
                        <td>{{ ucfirst($reg->account_type) }}</td>
                        <td>
                            @if($reg->account_type === 'company')
                                {{ $reg->organization_name ?? '-' }}
                            @else
                                {{ trim(($reg->title ? $reg->title . ' ' : '') . ($reg->first_name ?? '') . ' ' . ($reg->surname ?? '')) ?: '-' }}
                            @endif
                        </td>
                        <td>{{ $reg->email }}</td>
                        <td>
                            @php
                                $badge = match($reg->status) {
                                    'approved' => 'bg-success',
                                    'declined' => 'bg-danger',
                                    'invalid' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ ucfirst($reg->status ?? 'pending') }}</span>
                        </td>
                        <td>{{ $reg->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.registrations.show', $reg) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection
