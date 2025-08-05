<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AbortIfNotAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('sanctum')->check()) {
            
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
