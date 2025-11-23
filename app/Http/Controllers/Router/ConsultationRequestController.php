<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequestRequest;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\ConsultationRequest;
use App\Models\ConsultationTimeframe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ConsultationRequestController extends Controller
{
    public function create(Request $request)
    {
        $animals = $this->getUserAnimals($request);
        $animalTypes = AnimalType::all(['id', 'name']);
        $timeframes = ConsultationTimeframe::all(['id', 'name']);

        $props = [
            'animals' => $animals,
            'animalTypes' => $animalTypes,
            'timeframes' => $timeframes,
        ];

        return Inertia::render('ConsultationRequest/CreateConsultationRequest', $props);
    }

    private function getUserAnimals(Request $request)
    {
        if (!$request->user()) {
            return collect();
        }

        return Animal::where('user_id', $request->user()->id)
            ->with(['animalType:id'])
            ->get(['id', 'name', 'birthday', 'animal_type_id as animalTypeId']);
    }

    public function store(StoreConsultationRequestRequest $request)
    {
        $validated = $request->validated();

        try {
            $userWasCreated = false;

            DB::transaction(function () use ($validated, &$userWasCreated) {
                $user = $this->resolveUser($validated['user'], $userWasCreated);
                $animal = $this->resolveAnimal($validated['animal'], $user->id);

                ConsultationRequest::create([
                    'date' => $validated['date'],
                    'client_note' => $validated['observations'] ?? null,
                    'consultation_timeframe_id' => $validated['timeframe'],
                    'animal_id' => $animal->id,
                ]);
            });

            if ($userWasCreated) {
                return redirect()->route('login')
                    ->with('success', 'Consultation request created successfully! Please log in to your account.');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Consultation request created successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()
                ->withInput()
                ->with('error', 'Resource not found. Please check your selections.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', config('app.debug') ? $e->getMessage() : 'An error occurred while creating the consultation request. Please try again.');
        }
    }

    private function resolveUser(array $userData, &$userWasCreated): User
    {
        if ($userData['id']) {
            return User::findOrFail($userData['id']);
        }

        $existingUser = User::where('email', $userData['email'])->first();
        if ($existingUser) {
            return $existingUser;
        }

        $userWasCreated = true;

        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make('password'),
        ]);
    }

    private function resolveAnimal(array $animalData, string $userId): Animal
    {
        if ($animalData['id']) {
            return Animal::findOrFail($animalData['id']);
        }

        return Animal::create([
            'name' => $animalData['name'],
            'birthday' => $animalData['birthday'],
            'user_id' => $userId,
            'animal_type_id' => $animalData['animalTypeId'],
        ]);
    }
}