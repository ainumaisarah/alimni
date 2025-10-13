<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // User is not logged in, redirect to login
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            // User is logged in but not an admin, forbid access
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
