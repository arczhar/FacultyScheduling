<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('faculty')->check() && !Auth::user()->password_changed) {
            return redirect()->route('faculty.change-password');
        }

        return $next($request);
    }
}
