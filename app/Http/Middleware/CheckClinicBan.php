<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClinicBan
{
    public function handle(Request $request, Closure $next)
    {
        $clinic = auth('clinic')->user();

        if (!$clinic) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        if (in_array($routeName, ['clinic.banned', 'clinic.banAppeal.store'])) {
            return $next($request);
        }

        $activeBan = $clinic->bans()->active()->latest('created_at')->first();

        if ($activeBan) {
            return redirect()->route('clinic.banned');
        }

        return $next($request);
    }
}

