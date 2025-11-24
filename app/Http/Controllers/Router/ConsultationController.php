<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Consultations/IndexConsultations/IndexConsultations', []);
    }

    public function show(Request $request, $id)
    {
        $consultationRequest = ConsultationRequest::find($id);
        
        if ($consultationRequest) {
            return $this->showConsultationRequest($request, $consultationRequest);
        }

        $consultation = Consultation::findOrFail($id);
        
        return $this->showConsultation($request, $consultation);
    }

    private function showConsultationRequest(Request $request, ConsultationRequest $consultationRequest)
    {
        $this->authorize('view', $consultationRequest);
        return Inertia::render('Consultations/ShowConsultation/ShowConsultation', []);
    }

    private function showConsultation(Request $request, Consultation $consultation)
    {
        $this->authorize('view', $consultation);
        return Inertia::render('Consultations/ShowConsultation/ShowConsultationRequest', []);
    }
}