<?php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/registration/store', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/registration/{registration}', [RegistrationController::class, 'show'])->name('registration.show');

// Authentication Routes
Auth::routes(['verify' => true]);

// Dashboard route that redirects to home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// Home route
Route::get('/home', [HomeController::class, 'index'])
//  ->middleware(['auth', 'verified'])
->name('home');

 // Protected routes
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    // User-facing routes
    Route::get('/my/submissions', [RegistrationController::class, 'mySubmissions'])->name('my.submissions');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/photo', [UserController::class, 'updateProfilePhoto'])->name('profile.photo');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Admin routes for registrations review workflow
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/registrations', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'index'])->name('admin.registrations.index');
    Route::get('/registrations/{registration}', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'show'])->name('admin.registrations.show');
    Route::post('/registrations/{registration}/approve', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'approve'])->name('admin.registrations.approve');
    Route::post('/registrations/{registration}/decline', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'decline'])->name('admin.registrations.decline');
    Route::post('/registrations/{registration}/invalid', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'markInvalid'])->name('admin.registrations.invalid');
});

// Resubmission routes
Route::get('/resubmit/{token}', [\App\Http\Controllers\ResubmissionController::class, 'edit'])->name('resubmission.edit');
Route::post('/resubmit/{token}', [\App\Http\Controllers\ResubmissionController::class, 'update'])->name('resubmission.update');

// OTP passwordless login routes
Route::get('/login/otp/request', [\App\Http\Controllers\Auth\OtpAuthController::class, 'requestForm'])->name('otp.request');
Route::post('/login/otp/send-link', [\App\Http\Controllers\Auth\OtpAuthController::class, 'sendLink'])
    ->middleware('throttle:5,1')
    ->name('otp.sendLink');
Route::get('/login/otp/verify', [\App\Http\Controllers\Auth\OtpAuthController::class, 'verifyForm'])->name('otp.verifyForm');
Route::post('/login/otp/verify', [\App\Http\Controllers\Auth\OtpAuthController::class, 'verify'])
    ->middleware('throttle:6,1')
    ->name('otp.verify');

// Google OAuth routes
Route::get('/auth/google/redirect', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])
    ->middleware('guest')
    ->name('google.redirect');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])
    ->middleware('guest')
    ->name('google.callback');

// Protected file serving routes
Route::middleware(['auth'])->group(function () {
    Route::get('/files/registration/{registration}/{fileType}', [\App\Http\Controllers\FileController::class, 'serveRegistrationFile'])->name('files.registration');
    Route::get('/files/wireman-license/{license}', [\App\Http\Controllers\FileController::class, 'serveWiremanLicenseFile'])->name('files.wireman-license');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
