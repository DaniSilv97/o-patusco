<?php

use App\Http\Controllers\Router\DashboardController;
use App\Http\Controllers\Router\ConsultationRequestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name('home');
});


Route::get('/consultation', [ConsultationRequestController::class, 'create'])
    ->name('consultation');
Route::post('/consultation', [ConsultationRequestController::class, 'store'])
    ->name('consultation.store');

Route::middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware(['auth', 'verified', 'receptionist'])->group(function () {
    // Receptionist routes
});

Route::middleware(['auth', 'verified', 'veterinarian'])->group(function () {
    // Veterinarian routes
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
