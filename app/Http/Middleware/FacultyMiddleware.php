<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FacultyMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'faculty') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}
