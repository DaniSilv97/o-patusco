<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnimalResource;
use App\Http\Resources\ConsultationRequestResource;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request){
        $userId = $request->user()->id;
        
        $user = User::with([
            'animals' => fn($q) => $q->latest()->limit(6),
            'animals.animalType',
        ])->find($userId);

        $consultationRequests = ConsultationRequest::whereHas('animal', fn($q) => $q->where('user_id', $userId))
            ->doesntHave('consultation')
            ->with('timeframe')
            ->orderBy('created_at','desc')
            ->latest()
            ->limit(5)
            ->get();

        $consultations = Consultation::whereHas('consultationRequest.animal', fn($q) => $q->where('user_id', $userId))
            ->with('state')
            ->orderBy('created_at','desc')
            ->latest()
            ->limit(5)
            ->get()
            ->keyBy('id');

        $animalsCount = $user->animals()->count();
        $consultationRequestsCount = ConsultationRequest::whereHas('animal', fn($q) => $q->where('user_id', $userId))
            ->doesntHave('consultation')
            ->count();
        $consultationsCount = Consultation::whereHas('consultationRequest.animal', fn($q) => $q->where('user_id', $userId))
            ->count();

        return Inertia::render('Dashboard/ClientDashboard/Dashboard', [
            'user' => $user->only(['id', 'name', 'email']),
            'animals' => AnimalResource::collection($user->animals)->values(),
            'consultationRequests' => ConsultationRequestResource::collection($consultationRequests)->values(),
            'consultations' => ConsultationResource::collection($consultations)->values(),
            'counts' => [
                'animals' => $animalsCount,
                'consultationRequests' => $consultationRequestsCount,
                'consultations' => $consultationsCount,
            ],
        ]);
    }
}