@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Application #{{ $registration->id }}
                <span class="badge 
                    @if($registration->status === 'approved') bg-success 
                    @elseif($registration->status === 'declined') bg-danger 
                    @elseif($registration->status === 'invalid') bg-warning text-dark 
                    @else bg-secondary @endif">
                    {{ ucfirst($registration->status ?? 'pending') }}
                </span>
            </h4>
            <div class="text-muted">
                Submitted: {{ $registration->created_at?->format('Y-m-d H:i') }}
            </div>
        </div>
        <div class="card-body">
            <h5 class="mb-3">Applicant Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Account Type</dt>
                        <dd class="col-sm-8">{{ ucfirst($registration->account_type) }}</dd>

                        @if($registration->account_type === 'company')
                            <dt class="col-sm-4">Organization Name</dt>
                            <dd class="col-sm-8">{{ $registration->organization_name ?? '-' }}</dd>

                            <dt class="col-sm-4">Organization Type</dt>
                            <dd class="col-sm-8">{{ $registration->organization_type ?? '-' }}</dd>

                            <dt class="col-sm-4">Designation/Business</dt>
                            <dd class="col-sm-8">{{ $registration->designation_business ?? '-' }}</dd>

                            <dt class="col-sm-4">Registration No.</dt>
                            <dd class="col-sm-8">{{ $registration->com_reg_num ?? '-' }}</dd>
                        @else
                            <dt class="col-sm-4">Title</dt>
                            <dd class="col-sm-8">{{ $registration->title ?? '-' }}</dd>

                            <dt class="col-sm-4">First Name</dt>
                            <dd class="col-sm-8">{{ $registration->first_name ?? '-' }}</dd>

                            <dt class="col-sm-4">Surname</dt>
                            <dd class="col-sm-8">{{ $registration->surname ?? '-' }}</dd>
                        @endif

                        <dt class="col-sm-4">TIN Number</dt>
                        <dd class="col-sm-8">{{ $registration->tin_number ?? '-' }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $registration->email }}</dd>

                        <dt class="col-sm-4">Alt Email</dt>
                        <dd class="col-sm-8">{{ $registration->alt_email ?? '-' }}</dd>

                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">{{ $registration->address ?? '-' }}</dd>

                        <dt class="col-sm-4">Street</dt>
                        <dd class="col-sm-8">{{ $registration->street ?? '-' }}</dd>

                        <dt class="col-sm-4">Suburb</dt>
                        <dd class="col-sm-8">{{ $registration->suburb ?? '-' }}</dd>

                        <dt class="col-sm-4">City</dt>
                        <dd class="col-sm-8">{{ $registration->city?->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Region</dt>
                        <dd class="col-sm-8">{{ $registration->region?->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Office Phone</dt>
                        <dd class="col-sm-8">{{ $registration->office_phone ?? '-' }}</dd>

                        <dt class="col-sm-4">Mobile Phone</dt>
                        <dd class="col-sm-8">{{ $registration->mobile_phone ?? '-' }}</dd>
                    </dl>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Documents</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        @if($registration->roc_file_path)
                            <dt class="col-sm-4">ROC Certificate</dt>
                            <dd class="col-sm-8"><a href="{{ route('files.registration', [$registration->id, 'roc_certificate']) }}" target="_blank">View</a></dd>
                        @endif
                        @if($registration->tin_letter_path)
                            <dt class="col-sm-4">TIN Letter</dt>
                            <dd class="col-sm-8"><a href="{{ route('files.registration', [$registration->id, 'tin_letter']) }}" target="_blank">View</a></dd>
                        @endif
                        @if($registration->wireman_license_ind_path)
                            <dt class="col-sm-4">Wireman License (Individual)</dt>
                            <dd class="col-sm-8"><a href="{{ route('files.registration', [$registration->id, 'wireman_license_ind']) }}" target="_blank">View</a></dd>
                        @endif
                    </dl>
                </div>
                <div class="col-md-6">
                    @if($registration->wiremanLicenses->count())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>License Number</th>
                                    <th>File</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($registration->wiremanLicenses as $wl)
                                    <tr>
                                        <td>{{ $wl->license_number }}</td>
                                        <td>
                                            @if($wl->license_file_path)
                                                <a href="{{ route('files.wireman-license', $wl->id) }}" target="_blank">View</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Review</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    @if($registration->admin_comments)
                        <div class="alert alert-info">
                            <strong>Latest Admin Comments:</strong> {{ $registration->admin_comments }}
                        </div>
                    @endif
                    @if(is_array($registration->invalid_fields) && count($registration->invalid_fields))
                        <div class="alert alert-warning">
                            <strong>Fields Previously Marked Invalid:</strong>
                            {{ implode(', ', $registration->invalid_fields) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">Approve</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Comments (optional)</label>
                                    <textarea class="form-control" name="admin_comments" rows="3" placeholder="Optional comments to store with the approval."></textarea>
                                </div>
                                <button class="btn btn-success w-100" type="submit">Approve Application</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header bg-danger text-white">Decline</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.registrations.decline', $registration) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Comments (optional)</label>
                                    <textarea class="form-control" name="admin_comments" rows="3" placeholder="Provide a reason (sent to applicant)."></textarea>
                                </div>
                                <button class="btn btn-danger w-100" type="submit">Decline Application</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header bg-warning">Mark as Invalid</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.registrations.invalid', $registration) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Comments (required)</label>
                                    <textarea class="form-control" name="admin_comments" rows="3" required placeholder="Explain what needs to be fixed."></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Invalid Fields (one per line)</label>
                                    <div id="invalidFieldsContainer">
                                        <input type="text" name="invalid_fields[]" class="form-control mb-2" placeholder="e.g., tin_letter_path" required />
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" id="addInvalidField" type="button">+ Add field</button>
                                    <small class="text-muted d-block mt-2">
                                        Use registration column keys. Examples: email, tin_number, roc_file_path, wireman_license_ind_path, city_id, region_id, etc.
                                    </small>
                                </div>
                                <button class="btn btn-warning w-100" type="submit">Mark as Invalid & Send Link</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($registration->reviewed_by)
            <hr>
            <div class="text-muted">
                Reviewed by: {{ $registration->reviewedBy?->name ?? ('User ID ' . $registration->reviewed_by) }} on {{ $registration->reviewed_at?->format('Y-m-d H:i') }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('addInvalidField');
    const container = document.getElementById('invalidFieldsContainer');
    if (addBtn && container) {
        addBtn.addEventListener('click', function () {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'invalid_fields[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'e.g., tin_letter_path';
            container.appendChild(input);
        });
    }
});
</script>
@endsection
