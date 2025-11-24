<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ReceptionistController extends Controller
{
    public function indexDashboard(Request $request){
        return Inertia::render('Dashboard/ReceptionistDashboard/ReceptionistDashboard', []);
    }
}
