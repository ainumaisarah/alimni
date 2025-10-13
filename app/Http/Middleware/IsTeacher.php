<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'teacher') {
            return $next($request);
        }

        abort(403, 'Unauthorized - Only teachers can access this page.');
    }
}
