<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Clinic;
use App\Models\ClinicVerificationDenial;
use App\Models\PetOwner;

class AuthController extends Controller
{
    // -------------------------------
    // Show admin login form
    // -------------------------------
    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    // -------------------------------
    // Handle admin login (password-only)
    // -------------------------------
    public function adminLogin(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $admin = Admin::first(); // adjust if multiple admins exist

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Invalid password');
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    // -------------------------------
    // Show login form for other roles
    // -------------------------------
    public function showLoginForm($role)
    {
        return view('auth.login', compact('role'));
    }

    // -------------------------------
    // Login for clinic or pet owner
    // -------------------------------
    public function login(Request $request, $role)
    {
        // ✅ normalize role
        if ($role === 'clinic_staff') {
            $role = 'clinic';
        }

        $guard = '';
        $redirect = '';
        $credentials = [];

        // ✅ Track failed attempts in session
        $failedAttemptsKey = 'login_attempts_' . $role . '_' . $request->email;
        $failedAttempts = $request->session()->get($failedAttemptsKey, 0);

        switch ($role) {
            case 'clinic':
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required|string',
                ]);

                $guard = 'clinic';
                $redirect = 'clinic.dashboard';
                $credentials = $request->only('email', 'password');

                if (class_exists(\App\Models\ClinicVerificationDenial::class)) {
                    $denial = \App\Models\ClinicVerificationDenial::where('email', $request->email)->latest('denied_at')->first();
                    if ($denial) {
                        return redirect()->route('clinic.denied')->with([
                            'clinic_denied_name' => $denial->clinic_name,
                            'clinic_denied_reason' => $denial->reason,
                        ]);
                    }
                }

                if (!Auth::guard($guard)->attempt($credentials)) {
                    $failedAttempts++;
                    $request->session()->put($failedAttemptsKey, $failedAttempts);

                    $errorMessage = 'Invalid credentials. Please check your email and password.';
                    if ($failedAttempts >= 3) {
                        $errorMessage = 'Too many failed attempts. You may reset your password.';
                        return back()->withErrors(['email' => $errorMessage])->with('show_forgot_password', true);
                    }
                    return back()->withErrors(['email' => $errorMessage]);
                }

                // Reset on success
                $request->session()->forget($failedAttemptsKey);

                $clinicUser = Auth::guard($guard)->user();
                if ($clinicUser->verification_denied_at) {
                    $request->session()->regenerate();
                    return redirect()->route('clinic.denied');
                }

                if (!$clinicUser->is_verified) {
                    $request->session()->regenerate();
                    return redirect()->route('clinic.waiting')
                        ->with('message', 'Your clinic is still waiting for admin verification.');
                }

                if (!$clinicUser->is_subscribed || $clinicUser->subscriptionIsExpired()) {
                    $request->session()->regenerate();
                    return redirect()->route('clinic.subscription')
                        ->with('message', 'Your clinic subscription is inactive or has expired. Please renew to access the dashboard.');
                }

                $request->session()->regenerate();
                break;

            case 'pet_owner':
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required|string',
                ]);
                $guard = 'pet_owner';
                $redirect = 'pet_owner.dashboard';
                $credentials = $request->only('email', 'password');

                if (!Auth::guard($guard)->attempt($credentials)) {
                    $failedAttempts++;
                    $request->session()->put($failedAttemptsKey, $failedAttempts);

                    $errorMessage = 'Invalid credentials';
                    if ($failedAttempts >= 3) {
                        $errorMessage = 'Too many failed attempts. You may reset your password.';
                        return back()->withErrors(['email' => $errorMessage])->with('show_forgot_password', true);
                    }
                    return back()->withErrors(['email' => $errorMessage]);
                }

                // Reset on success
                $request->session()->forget($failedAttemptsKey);
                break;

            default:
                return back()->withErrors(['role' => 'Invalid role selected']);
        }

        $request->session()->regenerate();
        return redirect()->route($redirect);
    }

    // -------------------------------
    // Show signup form
    // -------------------------------
    public function showSignupForm($role)
    {
        return view('auth.signup', compact('role'));
    }

    // -------------------------------
    // Signup for all roles
    // -------------------------------
    public function signup(Request $request, $role)
    {
        // FIX: map clinic_staff to clinic
if ($role === 'clinic_staff') {
    $role = 'clinic';
}

        $user = null;
        $guard = null;
        $redirect = null;

        switch ($role) {
            case 'admin':
                $request->validate([
                    'username' => 'required|string|max:255',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'regex:/[A-Z]/',      // at least one uppercase
                        'regex:/[0-9]/',      // at least one number
                        'regex:/[@$!%*#?&]/', // at least one special character
                    ],
                ], [
                    'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
                ]);

                $user = Admin::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 'admin',
                ]);

                $guard = 'admin';
                $redirect = 'admin.dashboard';
                break;

            case 'clinic':
                $request->validate([
                    'username' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:clinics,email',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'regex:/[A-Z]/',      // at least one uppercase
                        'regex:/[0-9]/',      // at least one number
                        'regex:/[@$!%*#?&]/', // at least one special character
                    ],
                    'clinic_name' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ], [
                    'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
                ]);

                $uploadedDocs = [];
                if ($request->hasFile('documents')) {
                    foreach ($request->file('documents') as $file) {
                        $uploadedDocs[] = $file->store('clinic_documents', 'public');
                    }
                }

                $user = Clinic::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'clinic_name' => $request->clinic_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'is_verified' => false,
                    'documents' => $uploadedDocs,
                ]);

                $guard = 'clinic';
                $redirect = 'clinic.waiting';
                break;

            case 'pet_owner':
                $request->validate([
                    'username' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:pet_owners,email',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'regex:/[A-Z]/',      // at least one uppercase
                        'regex:/[0-9]/',      // at least one number
                        'regex:/[@$!%*#?&]/', // at least one special character
                    ],
                    'full_name' => 'nullable|string|max:255',
                    'phone' => 'required|string|max:20',
                    'address' => 'required|string|max:255',
                ], [
                    'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
                ]);

                $user = PetOwner::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'full_name' => $request->full_name ?? $request->username,
                    'phone' => $request->phone ?? '',
                    'address' => $request->address ?? '',
                ]);

                $guard = 'pet_owner';
                $redirect = 'pet_owner.dashboard';
                break;

            default:
                return back()->withErrors(['role' => 'Invalid role selected']);
        }

        Auth::guard($guard)->login($user);
        $request->session()->regenerate();

        return redirect()->route($redirect);
    }

    // -------------------------------
    // Logout
    // -------------------------------
    public function logout(Request $request)
    {
        foreach (['admin', 'clinic', 'pet_owner'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
