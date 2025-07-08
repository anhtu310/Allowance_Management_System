<?php

use App\Http\Controllers\Frontend\CustomerController;
use App\Models\AllowanceRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/admin/request/{id}/approve', function ($id) {
    $request = AllowanceRequest::findOrFail($id);
    $request->update([
        'status' => 'approved',
        'handled_by' => auth()->id(),
        'handled_at' => now(),
    ]);
    return back()->with('success', 'Request approved.');
})->name('request.approve');

Route::post('/admin/request/{id}/reject', function ($id) {
    $request = AllowanceRequest::findOrFail($id);
    $request->update([
        'status' => 'rejected',
        'handled_by' => auth()->id(),
        'handled_at' => now(),
    ]);
    return back()->with('success', 'Request rejected.');
})->name('request.reject');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/customer-info', [CustomerController::class, 'info'])->name('customer.info');
    Route::get('/customer-request', [CustomerController::class, 'requestForm'])->name('customer.request.form');
    Route::post('/customer-request', [CustomerController::class, 'submitRequest'])->name('customer.request.submit');
});

use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify', function () {
    return view('frontend.auth.verify');
})->middleware('auth:customer')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $customer = \App\Models\Customer::findOrFail($id);

    if (! $request->hasValidSignature()) {
        abort(403);
    }

    $customer->markEmailAsVerified();

    if (session()->has('pending_customer_update')) {
        $data = session()->pull('pending_customer_update');
        if ($data['email'] === $customer->email) {
            $customer->update([
                'name' => $data['name'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                'phone' => $data['phone'],
            ]);
        }
    }

    return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('customer')->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:customer', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth:customer', 'verified'])->group(function () {
    Route::get('/customer-info', [CustomerController::class, 'info'])->name('customer.info');
    Route::get('/customer-request', [CustomerController::class, 'requestForm'])->name('customer.request.form');
});

