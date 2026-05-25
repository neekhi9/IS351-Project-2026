<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration #{{ $registration->id }} - Summary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background:#f8f9fa; }
    .container-box { max-width: 900px; margin: 30px auto; background:#fff; padding: 24px 28px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,.08); }
    .section-title { font-size: 16px; text-transform: uppercase; color:#374151; letter-spacing:.06em; margin-top: 20px; margin-bottom: 8px; }
    .kv th { width: 260px; color:#111827; }
    .badge-type { background:#0A5C45; }
    .file-link { word-break: break-all; }
    .muted { color:#6b7280; }
  </style>
</head>
<body>
  <div class="container-box">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="m-0">Registration Summary</h2>
      <span class="text-muted">Ref #{{ $registration->id }}</span>
    </div>
    <div class="muted mb-3">{{ optional($registration->created_at)->toDayDateTimeString() }}</div>

    <div class="section-title">Overview</div>
    <table class="table table-borderless align-middle kv">
      <tbody>
        <tr>
          <th>Account Type</th>
          <td><span class="badge text-bg-success badge-type">{{ ucfirst($registration->account_type) }}</span></td>
        </tr>
        @if ($registration->account_type === 'company')
          <tr>
            <th>Organization</th>
            <td>{{ $registration->organization_name ?: '—' }}</td>
          </tr>
          <tr>
            <th>Contact</th>
            <td>{{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}</td>
          </tr>
          <tr>
            <th>Designation Business</th>
            <td>{{ $registration->designation_business ?: '—' }}</td>
          </tr>
          <tr>
            <th>Company Registration Number</th>
            <td>{{ $registration->com_reg_num ?: '—' }}</td>
          </tr>
          <tr>
            <th>TIN Number</th>
            <td>{{ $registration->tin_number ?: '—' }}</td>
          </tr>
        @else
          <tr>
            <th>Name</th>
            <td>{{ trim(($registration->title ?: '') . ' ' . ($registration->first_name ?: '') . ' ' . ($registration->surname ?: '')) ?: '—' }}</td>
          </tr>
          <tr>
            <th>TIN Number</th>
            <td>{{ $registration->tin_number ?: '—' }}</td>
          </tr>
        @endif
        <tr>
          <th>Email</th>
          <td>{{ $registration->email ?: '—' }}</td>
        </tr>
        <tr>
          <th>Alternate Email</th>
          <td>{{ $registration->alt_email ?: '—' }}</td>
        </tr>
        <tr>
          <th>Phone (Mobile / Office)</th>
          <td>
            @php
              $mobile = $registration->mobile_phone ?: null;
              $office = $registration->office_phone ?: null;
            @endphp
            {{ $mobile ?: '—' }}{{ $mobile && $office ? ' / ' : '' }}{{ $office ?: '' }}
          </td>
        </tr>
      </tbody>
    </table>

    <div class="section-title">Address</div>
    <table class="table table-borderless align-middle kv">
      <tbody>
        <tr>
          <th>Building/Lot</th>
          <td>{{ $registration->address ?: '—' }}</td>
        </tr>
        <tr>
          <th>Street</th>
          <td>{{ $registration->street ?: '—' }}</td>
        </tr>
        <tr>
          <th>Suburb</th>
          <td>{{ $registration->suburb ?: '—' }}</td>
        </tr>
        <tr>
          <th>City</th>
          <td>{{ optional($registration->city)->name ?? '—' }}</td>
        </tr>
        <tr>
          <th>Region</th>
          <td>{{ optional($registration->region)->name ?? '—' }}</td>
        </tr>
      </tbody>
    </table>

    @if ($registration->account_type === 'company')
      <div class="section-title">Directors</div>
      <div class="card mb-3">
        <div class="card-body">
          @if ($registration->directors && $registration->directors->count())
            <ul class="mb-0">
              @foreach ($registration->directors as $d)
                <li>{{ $d->name }}</li>
              @endforeach
            </ul>
          @else
            <div class="text-muted">No directors listed.</div>
          @endif
        </div>
      </div>
    @endif

    <div class="section-title">Wireman Licences</div>
    <div class="card mb-3">
      <div class="card-body">
        @php $wls = $registration->wiremanLicenses ?? collect(); @endphp
        @if ($wls->count())
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th style="width:240px;">Licence Number</th>
                  <th>File</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($wls as $wl)
                  <tr>
                    <td>{{ $wl->license_number ?: '—' }}</td>
                    <td>
                      @if(!empty($wl->license_file_path))
                        <a class="file-link" href="{{ route('files.wireman-license', $wl->id) }}" target="_blank" rel="noopener">View file</a>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-muted">No wireman licences provided.</div>
        @endif
        @if (!empty($registration->wireman_l_num_ind) || !empty($registration->wireman_license_ind_path))
          <hr />
          <div><strong>Individual Licence Number:</strong> {{ $registration->wireman_l_num_ind ?: '—' }}</div>
          <div>
            <strong>Individual Licence File:</strong>
            @if(!empty($registration->wireman_license_ind_path))
              <a class="file-link" href="{{ route('files.registration', [$registration->id, 'wireman_license_ind']) }}" target="_blank" rel="noopener">View file</a>
            @else
              <span class="text-muted">—</span>
            @endif
          </div>
        @endif
      </div>
    </div>

    <div class="section-title">Uploaded Documents</div>
    <div class="card mb-4">
      <div class="card-body">
        <div><strong>ROC Certificate:</strong>
          @if(!empty($registration->roc_file_path))
            <a class="file-link" href="{{ route('files.registration', [$registration->id, 'roc_certificate']) }}" target="_blank" rel="noopener">View file</a>
          @else
            <span class="text-muted">—</span>
          @endif
        </div>
        <div class="mt-2"><strong>TIN Letter:</strong>
          @if(!empty($registration->tin_letter_path))
            <a class="file-link" href="{{ route('files.registration', [$registration->id, 'tin_letter']) }}" target="_blank" rel="noopener">View file</a>
          @else
            <span class="text-muted">—</span>
          @endif
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('registration.create') }}" class="btn btn-secondary">Back to Registration Form</a>
    </div>
  </div>
</body>
</html>
