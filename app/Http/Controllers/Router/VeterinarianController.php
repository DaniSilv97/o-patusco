<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use Inertia\Inertia;

class VeterinarianController extends Controller
{
    public function indexDashboard(Request $request)
    {
        $perPage = 5;
        $consultationPage = $request->input('consultation_page', 1);
        $stateFilter = $request->input('state_filter', null);
        $user = $request->user();

        $query = Consultation::with(['state', 'consultationRequest.animal'])
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc');

        if ($stateFilter && $stateFilter !== 'all') {
            $query->whereHas('state', function ($q) use ($stateFilter) {
                $q->where('name', $stateFilter);
            });
        }

        $consultations = $query->paginate($perPage, ['*'], 'consultation_page', $consultationPage);

        $allConsultations = Consultation::with(['state', 'consultationRequest.animal'])
            ->where('user_id', $user->id)
            ->get();

        $allConsultationsData = ConsultationResource::collection($allConsultations)->response()->getData(true);

        return Inertia::render('Dashboard/VeterinarianDashboard/VeterinarianDashboard', [
            'consultations' => ConsultationResource::collection($consultations)->response()->getData(true),
            'stateFilter' => $stateFilter,
            'allConsultations' => $allConsultationsData['data'] ?? [],
        ]);
    }
}