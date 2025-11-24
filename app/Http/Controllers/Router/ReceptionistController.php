<?php

namespace App\Http\Controllers\Router;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceptionistController extends Controller
{
    public function indexDashboard(Request $request){
        dd('Index Receptionist Dashboard');
    }
}
