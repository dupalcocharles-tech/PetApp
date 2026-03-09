<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::guard('clinic')->check()) {
            return redirect()->route('clinic.dashboard');
        } elseif (Auth::guard('pet_owner')->check()) {
            return redirect()->route('pet_owner.dashboard');
        }

        return $next($request);
    }
}
