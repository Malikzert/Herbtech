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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

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

    Route::prefix('admin')->middleware(['can:admin', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
        
        Route::resource('raw-materials', RawMaterialController::class)->names('admin.raw-materials');
        Route::resource('products', ProductController::class)->names('admin.products');
        Route::resource('users', UserController::class)->names('admin.users');

        Route::get('productions', [ProductionController::class, 'index'])->name('admin.productions.index');
        Route::get('productions/{id}', [ProductionController::class, 'show'])->name('admin.productions.show');
        
        Route::get('qc', [QCController::class, 'index'])->name('admin.qc.index');
        Route::get('qc/{id}', [QCController::class, 'show'])->name('admin.qc.show');

        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    });

    Route::prefix('operator')->middleware(['can:operator', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'operator'])->name('operator.dashboard');

        Route::patch('productions/{id}/status', [ProductionController::class, 'updateStatus'])->name('operator.productions.updateStatus');
        Route::resource('productions', ProductionController::class)->names('operator.productions');
        Route::resource('qc', QCController::class)->names('operator.qc');

        Route::get('raw-materials', [RawMaterialController::class, 'index'])->name('operator.raw-materials.index');
        Route::get('products', [ProductController::class, 'index'])->name('operator.products.index');
    });
});