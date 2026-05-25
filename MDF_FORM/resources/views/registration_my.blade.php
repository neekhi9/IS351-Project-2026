@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">My Submissions</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($registrations->isEmpty())
        <div class="alert alert-info">
            No submissions found. You can submit a new application here:
            <a href="{{ route('registration.create') }}" class="alert-link">New Registration</a>
        </div>
    @else
        <div class="card">
            <div class="card-header">Submissions ({{ $registrations->count() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Account Type</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->id }}</td>
                                    <td class="text-capitalize">{{ $registration->account_type }}</td>
                                    <td>
                                        @if($registration->account_type === 'company')
                                            {{ $registration->organization_name ?? '—' }}
                                        @else
                                            {{ trim(($registration->title ? $registration->title . ' ' : '') . ($registration->first_name ?? '') . ' ' . ($registration->surname ?? '')) ?: '—' }}
                                        @endif
                                    </td>
                                    <td>{{ $registration->email }}</td>
                                    <td>
                                        @php
                                            $status = $registration->status ?? 'pending';
                                            $badge = match($status) {
                                                'approved' => 'success',
                                                'declined' => 'danger',
                                                'invalid' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>{{ optional($registration->created_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('registration.show', $registration) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
    @endif
</div>
@endsection
