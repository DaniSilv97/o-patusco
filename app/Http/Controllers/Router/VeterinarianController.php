<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VeterinarianController extends Controller
{
    public function indexDashboard(Request $request){
        dd('Index Veterinarian Dashboard');
    }
}
