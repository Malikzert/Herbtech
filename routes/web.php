<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\QCController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/forgot-password', [PasswordController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [PasswordController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [PasswordController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::post('/email/verify/resend', [PasswordController::class, 'resendVerificationEmail'])->name('verification.send');

    // Admin Routes
    Route::middleware(['can:admin', 'verified'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::resource('raw-materials', RawMaterialController::class);
        Route::resource('products', ProductController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Operator Routes
    Route::middleware(['can:operator', 'verified'])->group(function () {
        Route::get('/operator/dashboard', [DashboardController::class, 'operator'])->name('operator.dashboard');
        Route::resource('productions', ProductionController::class);
        Route::resource('qc', QCController::class);
    });
});
