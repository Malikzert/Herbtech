<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RawMaterialQcController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\QCController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SchedulingController;

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
        
        Route::get('raw-materials/qc', [RawMaterialQcController::class, 'adminIndex'])->name('admin.raw-materials.qc.index');
        Route::post('raw-materials/qc/{id}/resend', [RawMaterialQcController::class, 'adminResend'])->name('admin.raw-materials.qc.resend');
        Route::resource('raw-materials', RawMaterialController::class)->names('admin.raw-materials');
        Route::resource('products', ProductController::class)->names('admin.products');
        Route::resource('recipes', RecipeController::class)->names('admin.recipes');
        Route::post('recipes/store-batch', [RecipeController::class, 'storeBatch'])->name('admin.recipes.store-batch');
        Route::get('recipes/by-product/{productId}', [RecipeController::class, 'getByProduct'])->name('admin.recipes.by-product');
        Route::resource('users', UserController::class)->names('admin.users');

        Route::get('productions', [ProductionController::class, 'index'])->name('admin.productions.index');
        Route::get('productions/{production}', [ProductionController::class, 'show'])->name('admin.productions.show');
        Route::put('productions/{production}/update-status', [ProductionController::class, 'updateStatus'])->name('admin.productions.update-status');
        
        Route::get('scheduling', [ProductionController::class, 'schedulingIndex'])->name('admin.scheduling.index');
        Route::post('scheduling/generate', [SchedulingController::class, 'generate'])->name('admin.scheduling.generate');
        Route::post('scheduling/review', [ProductionController::class, 'reviewSchedule'])->name('admin.scheduling.review');
        
        Route::get('qc', [QCController::class, 'index'])->name('admin.qc.index');
        Route::get('qc/{id}', [QCController::class, 'show'])->name('admin.qc.show');

        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('admin.reports.export-csv');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
    });

    Route::prefix('operator')->middleware(['can:operator', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'operator'])->name('operator.dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('operator.profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('operator.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('operator.profile.password');

        Route::patch('productions/{production}/status', [ProductionController::class, 'updateStatus'])->name('operator.productions.updateStatus');
        Route::get('productions/{id}/recipe', [ProductionController::class, 'getRecipeByProduct'])->name('operator.productions.get-recipe');
        Route::resource('productions', ProductionController::class)->names('operator.productions');
        Route::resource('qc', QCController::class)->names('operator.qc');

        Route::get('schedules', [ScheduleController::class, 'index'])->name('operator.schedules.index');

        Route::get('raw-materials', [RawMaterialController::class, 'index'])->name('operator.raw-materials.index');
        Route::get('raw-materials/qc', [RawMaterialQcController::class, 'operatorIndex'])->name('operator.raw-materials.qc.index');
        Route::post('raw-materials/qc', [RawMaterialQcController::class, 'operatorStore'])->name('operator.raw-materials.qc.store');
        Route::get('products', [ProductController::class, 'index'])->name('operator.products.index');
    });
});