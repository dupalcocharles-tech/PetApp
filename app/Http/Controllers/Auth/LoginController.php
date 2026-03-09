<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Show login form (optional)
    public function showLoginForm()
    {
        return view('auth.login'); // your welcome/login page
    }

    // Handle login POST
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
        'role' => 'required|string',
    ]);

    // Normalize role (clinic_staff == clinic)
    $role = $request->role === 'clinic_staff' ? 'clinic' : $request->role;

    if (!in_array($role, ['admin', 'clinic', 'pet_owner'])) {
        return back()->withErrors(['role' => 'Invalid role selected']);
    }

    if (Auth::guard($role)->attempt([
        'email' => $request->email,
        'password' => $request->password,
    ])) {
        $request->session()->regenerate();

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'clinic':
                return redirect()->route('staff.dashboard'); // clinic + staff

            case 'pet_owner':
                return redirect()->route('pet_owner.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
}


    // Handle logout
    public function logout(Request $request)
    {
        $guard = 'web';

        if (Auth::guard('admin')->check()) {
            $guard = 'admin';
        } elseif (Auth::guard('clinic')->check()) {
            $guard = 'clinic';
        } elseif (Auth::guard('pet_owner')->check()) {
            $guard = 'pet_owner';
        }

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home'); // or your welcome page
    }
}
