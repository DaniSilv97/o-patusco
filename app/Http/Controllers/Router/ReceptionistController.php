<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultationRequestResource;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ConsultationState;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;

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

        $veterinarians = User::whereHas('roles', function ($query) {
            $query->where('name', 'Veterinário');
        })->select(['id', 'name'])->get();

        $conflicts = Consultation::with('veterinarian')
            ->select(['user_id', 'date'])
            ->get()
            ->map(function ($consultation) {
                return [
                    'veterinarian_id' => $consultation->user_id,
                    'date' => $consultation->date->format('Y-m-d'),
                    'time' => $consultation->date->format('H:i'),
                ];
            });

        return Inertia::render('Consultations/ShowConsultation/Receptionist/ShowConsultationRequest', [
            'consultationRequest' => new ConsultationRequestResource($consultationRequest),
            'veterinarians' => $veterinarians,
            'conflicts' => $conflicts,
        ]);
    }

    private function showConsultation(Request $request, Consultation $consultation)
    {
        $this->authorize('view', $consultation);

        $consultation->load(['state', 'consultationRequest.animal','veterinarian']);

        return Inertia::render('Consultations/ShowConsultation/Receptionist/ShowConsultation', [
            'consultation' => new ConsultationResource($consultation),
            'veterinarian'=> $consultation->veterinarian->name,
        ]);
    }

    public function createConsultation(Request $request, ConsultationRequest $consultationRequest)
    {
        $this->authorize('create', Consultation::class);

        // Validate the separate date and time fields
        $validated = $request->validate([
            'consultation_date' => 'required|date_format:Y-m-d',
            'consultation_time' => 'required|date_format:H:i',
            'veterinarian_id' => 'required|exists:users,id',
        ]);

        try {
            // Combine date and time into a single datetime
            $consultationDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $validated['consultation_date'] . ' ' . $validated['consultation_time']
            );

            $hour = $consultationDateTime->hour;
            $minute = $consultationDateTime->minute;

            if ($hour < 8 || ($hour === 17 && $minute > 30) || $hour > 17) {
                return back()->withErrors([
                    'consultation_date' => 'Horário deve estar entre 08:00 e 17:30',
                ]);
            }

            if ($minute !== 0 && $minute !== 30) {
                return back()->withErrors([
                    'consultation_date' => 'Horário deve ser em intervalos de 30 minutos',
                ]);
            }

            $existingConsultation = Consultation::where('user_id', $validated['veterinarian_id'])
                ->where('date', $consultationDateTime)
                ->first();

            if ($existingConsultation) {
                return back()->withErrors([
                    'consultation_date' => 'O veterinário já possui uma consulta neste horário',
                ]);
            }

            $veterinarian = User::findOrFail($validated['veterinarian_id']);
            if (!$veterinarian->hasRole('Veterinário')) {
                return back()->withErrors([
                    'veterinarian_id' => 'Utilizador selecionado não é um veterinário',
                ]);
            }

            $state = ConsultationState::where('name', 'Atribuída')->first();
            
            $consultation = Consultation::create([
                'date' => $consultationDateTime,
                'consultation_state_id' => $state->id,
                'consultation_request_id' => $consultationRequest->id,
                'user_id' => $validated['veterinarian_id'],
                'veterinarian_note' => ''
            ]);

            return redirect()->route('receptionist.consultation', $consultation->id)
                ->with('success', 'Consulta criada com sucesso');

        } catch (\Exception $e) {
            return back()->withErrors([
                'consultation_date' => 'Erro ao criar consulta: ' . $e->getMessage(),
            ]);
        }
    }
}