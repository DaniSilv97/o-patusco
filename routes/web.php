<?php

use App\Http\Controllers\Router\ReceptionistController;
use App\Http\Controllers\Router\AnimalController;
use App\Http\Controllers\Router\ConsultationController;
use App\Http\Controllers\Router\DashboardController;
use App\Http\Controllers\Router\ConsultationRequestController;
use App\Http\Controllers\Router\VeterinarianController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $user = Auth::user();

    if ($user) {
        if ($user->can('is-client')) {
            return redirect()->route('dashboard');
        } elseif ($user->can('is-receptionist')) {
            return redirect()->route('receptionist.dashboard');
        } elseif ($user->can('is-veterinarian')) {
            return redirect()->route('veterinarian.dashboard');
        }
    }
    
    return Inertia::render('Welcome');
})->name('home');


Route::get('/consultation', [ConsultationRequestController::class, 'create'])
    ->name('consultation');
Route::post('/consultation', [ConsultationRequestController::class, 'store'])
    ->name('consultation.store');

Route::middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/animals', [AnimalController::class, 'index'])
        ->name('animals');
    Route::get('/animals/{animal}', [AnimalController::class, 'show'])
        ->name('animals.show');
    Route::get('/consultations', [ConsultationController::class, 'index'])
        ->name('consultations');
});

Route::middleware(['auth', 'verified', 'receptionist'])->group(function () {
    Route::get('/receptionist/dashboard', [ReceptionistController::class, 'indexDashboard'])
        ->name('receptionist.dashboard');
});

Route::middleware(['auth', 'verified', 'veterinarian'])->group(function () {
    Route::get('/veterinarian/dashboard', [VeterinarianController::class, 'indexDashboard'])
        ->name('veterinarian.dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/consultations/{id}', [ConsultationController::class, 'show'])
        ->name('consultations.show');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
