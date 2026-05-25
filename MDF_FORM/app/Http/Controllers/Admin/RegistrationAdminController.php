<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use App\Services\Mail\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegistrationAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * List registrations with optional status filter.
     */
    public function index(Request $request)
    {
        $query = Registration::query()->with(['city', 'region']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Show full registration detail.
     */
    public function show(Registration $registration)
    {
        $registration->loadMissing(['city', 'region', 'directors', 'wiremanLicenses', 'reviewedBy']);

        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Approve a registration.
     */
    public function approve(Request $request, Registration $registration, PHPMailerService $mailer)
    {
        $this->authorizeAction('registration-approve');

        DB::transaction(function () use ($request, $registration, $mailer) {
            // Create or locate user by registration email
            $email = $registration->email;
            $name = trim(implode(' ', array_filter([$registration->first_name, $registration->surname])));

            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $name !== '' ? $name : ($registration->organization_name ?: 'User'),
                    'email' => $email,
                    // Random password placeholder; users will login via OTP
                    'password' => bcrypt(Str::random(32)),
                ]);
            }

            // Ensure 'user' role exists and is assigned
            $userRole = Role::firstOrCreate(['name' => 'user']);
            if (!$user->hasRole($userRole->name)) {
                $user->assignRole($userRole);
            }

            // Update registration status and reviewer info
            $registration->status = 'approved';
            $registration->reviewed_by = auth()->id();
            $registration->reviewed_at = now();
            $registration->admin_comments = $request->string('admin_comments') ?: null;
            $registration->save();

            // Send approved email with OTP login link
            $loginLink = route('otp.request', ['email' => $user->email]);
            if (method_exists($mailer, 'sendApprovedNotice')) {
                $mailer->sendApprovedNotice($user, $loginLink);
            }
        });

        return redirect()
            ->route('admin.registrations.show', $registration)
            ->with('success', 'Registration approved and applicant notified.');
    }

    /**
     * Decline a registration with optional comments.
     */
    public function decline(Request $request, Registration $registration, PHPMailerService $mailer)
    {
        $this->authorizeAction('registration-decline');

        $request->validate([
            'admin_comments' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($request, $registration, $mailer) {
            $registration->status = 'declined';
            $registration->reviewed_by = auth()->id();
            $registration->reviewed_at = now();
            $registration->admin_comments = $request->string('admin_comments') ?: null;
            $registration->save();

            if (method_exists($mailer, 'sendDeclinedNotice')) {
                $mailer->sendDeclinedNotice($registration);
            }
        });

        return redirect()
            ->route('admin.registrations.show', $registration)
            ->with('success', 'Registration declined and applicant notified.');
    }

    /**
     * Mark a registration as invalid, storing invalid fields and sending a resubmission link.
     */
    public function markInvalid(Request $request, Registration $registration, PHPMailerService $mailer)
    {
        $this->authorizeAction('registration-mark-invalid');

        $validated = $request->validate([
            'admin_comments' => 'required|string|max:2000',
            'invalid_fields' => 'required|array',
            'invalid_fields.*' => 'string',
        ]);

        DB::transaction(function () use ($validated, $registration, $mailer) {
            $registration->status = 'invalid';
            $registration->reviewed_by = auth()->id();
            $registration->reviewed_at = now();
            $registration->admin_comments = $validated['admin_comments'];
            $registration->invalid_fields = array_values($validated['invalid_fields']);

            // Generate a unique resubmission token and expiry (7 days by default)
            $registration->resubmission_token = Str::uuid()->toString();
            $registration->resubmission_expires_at = now()->addDays(7);
            $registration->save();

            $resubmissionLink = route('resubmission.edit', ['token' => $registration->resubmission_token]);

            if (method_exists($mailer, 'sendInvalidNotice')) {
                $mailer->sendInvalidNotice($registration, $resubmissionLink);
            }
        });

        return redirect()
            ->route('admin.registrations.show', $registration)
            ->with('success', 'Registration marked invalid. Resubmission link sent to applicant.');
    }

    protected function authorizeAction(string $permission): void
    {
        // Use Spatie permissions if installed; fallback allows action for admins
        if (auth()->user()?->can($permission)) {
            return;
        }
        if (auth()->user()?->hasRole('admin')) {
            // Admins usually have all permissions; allow
            return;
        }
        abort(403, 'Unauthorized');
    }
}
