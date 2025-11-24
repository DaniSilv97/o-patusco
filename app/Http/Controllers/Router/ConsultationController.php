<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateConsultationRequestRequest;
use App\Http\Resources\ConsultationRequestResource;
use App\Http\Resources\ConsultationResource;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationTimeframe;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $perPage = 5;
        $consultationRequestPage = $request->input('consultation_request_page', 1);
        $consultationPage = $request->input('consultation_page', 1);

        $day = $request->input('day');
        $animalId = $request->input('animal_id');
        $animalTypeId = $request->input('animal_type_id');

        $consultationDay = $request->input('consultation_day');
        $consultationAnimalId = $request->input('consultation_animal_id');
        $consultationAnimalTypeId = $request->input('consultation_animal_type_id');

        $consultationRequestsQuery = ConsultationRequest::with(['timeframe', 'animal'])
            ->whereHas('animal', fn($q) => $q->where('user_id', $userId))
            ->doesntHave('consultation')
            ->orderBy('created_at', 'desc');

        if ($day) {
            $consultationRequestsQuery->whereDate('date', $day);
        }

        if ($animalId) {
            $consultationRequestsQuery->where('animal_id', $animalId);
        }

        if ($animalTypeId) {
            $consultationRequestsQuery->whereHas('animal', function ($query) use ($animalTypeId) {
                $query->where('animal_type_id', $animalTypeId);
            });
        }

        $consultationRequests = $consultationRequestsQuery->paginate(
            $perPage,
            ['*'],
            'consultation_request_page',
            $consultationRequestPage
        );

        $consultationsQuery = Consultation::with(['state', 'consultationRequest.animal'])
            ->whereHas('consultationRequest.animal', fn($q) => $q->where('user_id', $userId))
            ->orderBy('date', 'desc');

        if ($consultationDay) {
            $consultationsQuery->whereDate('date', $consultationDay);
        }

        if ($consultationAnimalId) {
            $consultationsQuery->whereHas('consultationRequest.animal', function ($query) use ($consultationAnimalId) {
                $query->where('id', $consultationAnimalId);
            });
        }

        if ($consultationAnimalTypeId) {
            $consultationsQuery->whereHas('consultationRequest.animal', function ($query) use ($consultationAnimalTypeId) {
                $query->where('animal_type_id', $consultationAnimalTypeId);
            });
        }

        $consultations = $consultationsQuery->paginate(
            $perPage,
            ['*'],
            'consultation_page',
            $consultationPage
        );

        $animals = Animal::where('user_id', $userId)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $animalTypes = AnimalType::orderBy('name')
            ->select(['id', 'name'])
            ->get();

        return Inertia::render('Consultations/IndexConsultations/IndexConsultations', [
            'consultationRequests' => ConsultationRequestResource::collection($consultationRequests)->response()->getData(true),
            'consultations' => ConsultationResource::collection($consultations)->response()->getData(true),
            'animals' => $animals,
            'animalTypes' => $animalTypes,
        ]);
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