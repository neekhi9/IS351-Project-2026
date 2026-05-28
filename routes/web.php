<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KitchenOrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Auth::routes(['verify' => true]);

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// OTP passwordless login routes
Route::get('/login/otp/request', [OtpAuthController::class, 'requestForm'])->name('otp.request');
Route::post('/login/otp/send-link', [OtpAuthController::class, 'sendLink'])
    ->middleware('throttle:5,1')
    ->name('otp.sendLink');
Route::get('/login/otp/verify', [OtpAuthController::class, 'verifyForm'])->name('otp.verifyForm');
Route::post('/login/otp/verify', [OtpAuthController::class, 'verify'])
    ->middleware('throttle:6,1')
    ->name('otp.verify');

// Google OAuth routes (preserved)
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->middleware('guest')
    ->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->middleware('guest')
    ->name('google.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // Customer flows
    Route::middleware('role:customer')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });

    // Staff flows
    Route::middleware('role:staff|admin')->group(function () {
        Route::get('/kitchen', [KitchenOrderController::class, 'index'])->name('kitchen.index');
        Route::patch('/kitchen/orders/{order}/status', [KitchenOrderController::class, 'updateStatus'])->name('kitchen.orders.updateStatus');
    });

    // Admin flows
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::resource('roles', RoleController::class)->names([
            'index' => 'roles.index',
            'create' => 'roles.create',
            'store' => 'roles.store',
            'show' => 'roles.show',
            'edit' => 'roles.edit',
            'update' => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);

        Route::resource('users', UserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu.index');
        Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
        Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
        Route::get('/menu/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
        Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    });

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/photo', [UserController::class, 'updateProfilePhoto'])->name('profile.photo');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});
