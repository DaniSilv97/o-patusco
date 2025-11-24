<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConsultationRequestRequest;
use App\Http\Resources\ConsultationRequestResource;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationTimeframe;
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

        $consultationRequest->load(['animal', 'timeframe']);

        $timeframes = ConsultationTimeframe::all(['id', 'name']);

        return Inertia::render('Consultations/ShowConsultation/Client/ShowConsultationRequest', [
            'consultationRequest' => new ConsultationRequestResource($consultationRequest),
            'timeframes' => $timeframes,
        ]);
    }

    private function showConsultation(Request $request, Consultation $consultation)
    {
        $this->authorize('view', $consultation);

        $consultation->load(['state', 'consultationRequest.animal','veterinarian']);

        return Inertia::render('Consultations/ShowConsultation/Client/ShowConsultation', [
            'consultation' => new ConsultationResource($consultation),
            'veterinarian'=> $consultation->veterinarian->name,
        ]);
    }

    public function updateConsultationRequest(UpdateConsultationRequestRequest $request, $id)
    {
        $consultationRequest = ConsultationRequest::findOrFail($id);
        
        $this->authorize('update', $consultationRequest);

        $validated = $request->validated();

        $consultationRequest->update([
            'date' => $validated['date'],
            'consultation_timeframe_id' => $validated['consultation_timeframe_id'],
            'client_note' => $validated['client_note'],
        ]);

        return redirect()->route('consultation', $consultationRequest->id)
            ->with('success', 'Pedido de consulta atualizado com sucesso');
    }

    public function deleteConsultationRequest(Request $request, $id)
    {
        $consultationRequest = ConsultationRequest::findOrFail($id);
        
        $this->authorize('delete', $consultationRequest);

        $consultationRequest->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Pedido de consulta eliminado com sucesso');
    }
}