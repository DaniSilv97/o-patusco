<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index(Request $request){
        dd('Index Animal');
    }

    public function show(Request $request, Animal $animal)
    {
        dd('Show Animal', $animal->id);
        $this->authorize('view', $animal);
    }
}
