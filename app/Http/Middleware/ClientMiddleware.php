<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->can('is-client')) {
            return $next($request);
        }

        $user = $request->user();
        
        if ($user?->can('is-receptionist')) {
            return redirect()->route('receptionist.dashboard')->with('error', '403 | UNAUTHORIZED');
        } elseif ($user?->can('is-veterinarian')) {
            return redirect()->route('veterinarian.dashboard')->with('error', '403 | UNAUTHORIZED');
        }

        return redirect('/')->with('error', '403 | UNAUTHORIZED');
    }
}