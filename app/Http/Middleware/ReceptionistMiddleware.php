<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->can('is-receptionist')) {
            return $next($request);
        }

        $user = $request->user();
        
        if ($user?->can('is-client')) {
            return redirect()->route('dashboard')->with('error', '403 | UNAUTHORIZED');
        } elseif ($user?->can('is-veterinarian')) {
            return redirect()->route('veterinarian.dashboard')->with('error', '403 | UNAUTHORIZED');
        }

        return redirect('/')->with('error', '403 | UNAUTHORIZED');
    }
}