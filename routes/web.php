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


Route::get('/consultation/create', [ConsultationRequestController::class, 'create'])
    ->name('consultation.create');
Route::post('/consultation/store', [ConsultationRequestController::class, 'store'])
    ->name('consultation.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('client')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/animals', [AnimalController::class, 'index'])
            ->name('animals');
        Route::get('/animals/create', [AnimalController::class, 'create'])
            ->name('animals.create');
        Route::post('/animals/create', [AnimalController::class, 'store'])
            ->name('animals.store');
        Route::delete('/animals/delete', [AnimalController::class, 'destroy'])
            ->name('animals.store');
        Route::put('/animals/update/{animal}', [AnimalController::class, 'update'])
            ->name('animals.update');
        Route::get('/animals/{animal}', [AnimalController::class, 'show'])
            ->name('animals.show');
        Route::get('/consultations', [ConsultationController::class, 'index'])
            ->name('consultations');
        Route::get('/consultation/{id}', [ConsultationController::class, 'show'])
            ->name('consultation');
        Route::put('/consultation/update/{id}', [ConsultationController::class, 'updateConsultationRequest'])
            ->name('consultation.update');
        Route::delete('/consultation/delete/{id}', [ConsultationController::class, 'deleteConsultationRequest'])
            ->name('consultation.delete');
    });
    Route::middleware('receptionist')->prefix('receptionist')->group(function () {
        Route::get('/dashboard', [ReceptionistController::class, 'indexDashboard'])
            ->name('receptionist.dashboard');
        Route::get('/consultation/{id}', [ReceptionistController::class, 'showConsultation'])
            ->name('receptionist.consultation');
    });

    Route::middleware('veterinarian')->prefix('veterinarian')->group(function () {
        Route::get('/dashboard', [VeterinarianController::class, 'indexDashboard'])
            ->name('veterinarian.dashboard');
        Route::get('/consultation/{id}', [VeterinarianController::class, 'showConsultation'])
            ->name('veterinarian.consultation');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/consultations/{id}', [ConsultationController::class, 'show'])
        ->name('consultations.show');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
