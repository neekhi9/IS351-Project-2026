@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Fix Your Application</h2>

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

    <div class="alert alert-info">
        Please correct the fields listed below and resubmit. Fields not listed are locked and do not require changes.
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('resubmission.update', ['token' => $token]) }}" enctype="multipart/form-data">
                @csrf

                @php
                    $fileFields = ['roc_file_path', 'tin_letter_path', 'wireman_license_ind_path'];
                    $textFields = [
                        'email','mobile_phone','office_phone','tin_number','com_reg_num','organization_name',
                        'organization_type','designation_business','title','first_name','surname','address','street','suburb',
                        'wireman_l_num_ind'
                    ];
                    $selectFields = ['city_id','region_id'];
                @endphp

                @forelse($invalidFields as $field)
                    <div class="mb-3">
                        @php
                            $label = ucfirst(str_replace(['_', 'id'], [' ', 'ID'], $field));
                        @endphp

                        @if(in_array($field, $fileFields, true))
                            <label class="form-label">{{ $label }} (Upload New File)</label>
                            <input type="file" name="{{ $field }}" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                            @if(!empty($registration[$field]))
                                @php
                                    $fileType = match($field) {
                                        'roc_file_path' => 'roc_certificate',
                                        'tin_letter_path' => 'tin_letter',
                                        'wireman_license_ind_path' => 'wireman_license_ind',
                                        default => null
                                    };
                                @endphp
                                @if($fileType)
                                    <small class="text-muted d-block mt-1">Current: <a href="{{ route('files.registration', [$registration->id, $fileType]) }}" target="_blank">View existing file</a></small>
                                @endif
                            @endif

                        @elseif(in_array($field, $selectFields, true))
                            <label class="form-label">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" class="form-control" value="{{ old($field, $registration[$field] ?? '') }}" placeholder="Enter {{ strtolower($label) }}">
                            <small class="text-muted">Enter the numeric ID for {{ strtolower($label) }}.</small>

                        @else
                            <label class="form-label">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field, $registration[$field] ?? '') }}" placeholder="Enter {{ strtolower($label) }}">
                        @endif
                    </div>
                @empty
                    <div class="alert alert-secondary">
                        No specific invalid fields were provided. If you believe this is an error, please contact support.
                    </div>
                @endforelse

                <button type="submit" class="btn btn-primary">Submit Corrections</button>
                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <hr>

    <div class="mt-3">
        <h5>Application Summary</h5>
        <dl class="row">
            <dt class="col-sm-3">Reference</dt>
            <dd class="col-sm-9">#{{ $registration->id }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
                <span class="badge bg-warning text-dark">{{ ucfirst($registration->status) }}</span>
            </dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{ $registration->email }}</dd>

            <dt class="col-sm-3">Invalid Fields</dt>
            <dd class="col-sm-9">{{ implode(', ', $invalidFields) }}</dd>
        </dl>
    </div>
</div>
@endsection
