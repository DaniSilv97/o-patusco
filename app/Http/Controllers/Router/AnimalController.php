<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    public function index(Request $request){
        return Inertia::render('Animals/IndexAnimals/IndexAnimals', []);
    }

    public function show(Request $request, Animal $animal)
    {
        $this->authorize('view', $animal);
        $animal = $animal->load('animalType');
        return Inertia::render('Animals/ShowAnimal/ShowAnimal', [
            'animal'=> $animal,
        ]);
    }
}
