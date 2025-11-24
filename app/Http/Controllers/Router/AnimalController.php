<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Models\AnimalType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        
        $query = Animal::where("user_id", $user->id)
            ->with('animalType');
        
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        
        if ($request->filled('animal_type')) {
            $query->where('animal_type_id', $request->input('animal_type'));
        }
        
        $animals = $query->orderBy('created_at', 'desc')->get();
        
        $animalTypes = AnimalType::all();

        return Inertia::render('Animals/IndexAnimals/IndexAnimals', [
            'animals' => AnimalResource::collection($animals)->toArray(request()),
            'animalTypes' => $animalTypes,
        ]);
    }

    public function show(Request $request, Animal $animal)
    {
        $this->authorize('view', $animal);
        $animal = $animal->load('animalType');
        $animalTypes = AnimalType::all();
    
        return Inertia::render('Animals/ShowAnimal/ShowAnimal', [
            'animal' => new AnimalResource($animal),
            'animalTypes' => $animalTypes,
        ]);
    }

    public function create(Request $request)
    {
        $animalTypes = AnimalType::all();
        
        return Inertia::render('Animals/CreateAnimal/CreateAnimal', [
            'animalTypes' => $animalTypes,
        ]);
    }

    public function store(StoreAnimalRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        
        $animal = Animal::create([
            'name' => $validated['animal']['name'],
            'animal_type_id' => $validated['animal']['animalTypeId'],
            'birthday' => $validated['animal']['birthday'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('animals.show', $animal->id)
            ->with('success', "O animal '{$animal->name}' foi registado com sucesso!");
    }

    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        $this->authorize('update', $animal);
        
        $validated = $request->validated();
        
        $animal->update([
            'name' => $validated['name'] ?? $animal->name,
            'animal_type_id' => $validated['animal_type_id'] ?? $animal->animal_type_id,
            'birthday' => $validated['birthday'] ?? $animal->birthday,
        ]);

        return redirect()->route('animals', $animal->id)
            ->with('success', "O animal '{$animal->name}' foi atualizado com sucesso!");
    }

    public function destroy(Request $request, Animal $animal)
    {
        $this->authorize('delete', $animal);

        return redirect()->route('animals')
            ->with('error', "O animal não foi eliminado (Ainda não foram implementados soft deletes)");
    }
}