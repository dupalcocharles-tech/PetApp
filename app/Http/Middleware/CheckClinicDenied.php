<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClinicDenied
{
    public function handle(Request $request, Closure $next)
    {
        $clinic = auth('clinic')->user();

        if (!$clinic) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        if (in_array($routeName, ['clinic.denied', 'clinic.banned', 'clinic.banAppeal.store'], true)) {
            return $next($request);
        }

        if ($clinic->verification_denied_at) {
            return redirect()->route('clinic.denied');
        }

        return $next($request);
    }
}

