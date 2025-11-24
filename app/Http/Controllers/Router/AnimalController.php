<?php

namespace App\Http\Controllers\Router;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index(Request $request){
        //
    }

    public function show(Request $request, Animal $animal)
    {
        $this->authorize('view', $animal);
    }
}
