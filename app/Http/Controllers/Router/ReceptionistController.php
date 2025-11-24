<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultationRequestResource;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use Inertia\Inertia;

class ReceptionistController extends Controller
{
    public function indexDashboard(Request $request){
        $perPage = 5;
        $consultationRequestPage = $request->input('consultation_request_page', 1);
        $consultationPage = $request->input('consultation_page', 1);

        $consultationRequests = ConsultationRequest::with(['timeframe', 'animal'])
            ->doesntHave('consultation')
            ->orderBy('date', 'desc')
            ->paginate($perPage, ['*'], 'consultation_request_page', $consultationRequestPage);

        $consultations = Consultation::with(['state', 'consultationRequest.animal'])
            ->orderBy('date', 'desc')
            ->paginate($perPage, ['*'], 'consultation_page', $consultationPage);

        return Inertia::render('Dashboard/ReceptionistDashboard/ReceptionistDashboard', [
            'consultationRequests' => ConsultationRequestResource::collection($consultationRequests)->response()->getData(true),
            'consultations' => ConsultationResource::collection($consultations)->response()->getData(true),
        ]);
    }
}