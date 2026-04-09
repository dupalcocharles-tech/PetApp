<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Clinic;
use App\Models\PetOwner;
use App\Models\Appointment;
use App\Models\ClinicBan;
use App\Models\ClinicBanAppeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    /**
     * Handle password-only login
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $admin = Admin::first();

        if (!$admin) {
            return back()->withErrors(['password' => 'No admin found.']);
        }

        if (Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();

        // Quick stats
        $totalClinics = Clinic::count();
        $verifiedClinics = Clinic::where('is_verified', 1)->count();
        $unverifiedClinics = Clinic::where('is_verified', 0)->count();
        $totalPetOwners = PetOwner::count();
        $upcomingAppointments = Appointment::where('appointment_date', '>=', now())->count();

        // Pending clinics (unverified)
        $clinics = Clinic::where('is_verified', 0)->get();

        // ✅ Verified clinics (matches Blade variable)
        $verifiedClinicList = Clinic::where('is_verified', 1)->get();

        // Appointments per clinic chart
        $appointmentsPerClinic = Appointment::selectRaw('clinic_id, COUNT(*) as total')
            ->groupBy('clinic_id')
            ->with('clinic')
            ->get();

        $appointmentsPerClinicLabels = $appointmentsPerClinic->map(fn($a) => $a->clinic->clinic_name ?? 'Unknown')->toArray();
        $appointmentsPerClinicData = $appointmentsPerClinic->pluck('total')->toArray();

        return view('admin.dashboard', compact(
            'admin',
            'totalClinics',
            'verifiedClinics',
            'unverifiedClinics',
            'totalPetOwners',
            'upcomingAppointments',
            'clinics',
            'verifiedClinicList', // ✅ Correct variable name
            'appointmentsPerClinicLabels',
            'appointmentsPerClinicData'
        ));
    }

    /**
     * Show Add Admin using existing signup flow
     */
    public function create()
    {
        return redirect()->route('auth.signup', ['role' => 'admin']);
    }

    /**
     * Show Admin Settings placeholder view
     */
    public function edit()
    {
        return view('admin.users');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'qr_code' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            if ($admin->qr_code && Storage::disk('public')->exists('admins/qr_codes/' . $admin->qr_code)) {
                Storage::disk('public')->delete('admins/qr_codes/' . $admin->qr_code);
            }

            $file = $request->file('qr_code');
            $filename = time() . '_qr_' . $file->getClientOriginalName();
            $file->storeAs('admins/qr_codes', $filename, 'public');

            $admin->qr_code = $filename;
        }

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }
        $admin->save();

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Settings updated. Please login again.');
    }

    /**
     * ✅ Verify a clinic
     */
    public function verifyClinic($id)
    {
        $clinic = Clinic::findOrFail($id);
        if (!$clinic->subscription_receipt) {
            return redirect()->back()->with('error', 'Clinic cannot be verified until subscription payment proof is uploaded.');
        }

        $clinic->is_verified = 1;
        $clinic->save();

        return redirect()->back()->with('success', 'Clinic verified successfully!');
    }

    public function approveSubscription($id)
    {
        $clinic = Clinic::findOrFail($id);

        $now = now();
        $clinic->subscription_started_at = $now;
        $clinic->subscription_expires_at = (clone $now)->addMonth();
        $clinic->is_subscribed = 1;
        $clinic->save();

        return redirect()->back()->with('success', 'Clinic subscription approved successfully! Valid for 1 month.');
    }

    public function testSubscriptionExpiry($id)
    {
        $clinic = Clinic::findOrFail($id);

        if ($clinic->is_subscribed && $clinic->subscription_expires_at) {
            $clinic->subscription_expires_at = now()->subMinute();
            $clinic->save();

            return redirect()->back()->with('success', 'Clinic subscription marked as expired for testing.');
        }

        $now = now();
        $clinic->subscription_started_at = $now;
        $clinic->subscription_expires_at = (clone $now)->addDays(3);
        $clinic->is_subscribed = 1;
        $clinic->save();

        return redirect()->back()->with('success', 'Clinic subscription test period set for 3 days.');
    }

    /**
     * ❌ Delete a clinic
     */
    public function deleteClinic($id)
    {
        $clinic = Clinic::findOrFail($id);

        // Optionally delete stored documents
        if (!empty($clinic->documents)) {
            foreach ($clinic->documents as $doc) {
                if (Storage::disk('public')->exists($doc)) {
                    Storage::disk('public')->delete($doc);
                }
            }
        }

        $clinic->delete();

        return redirect()->back()->with('success', 'Clinic deleted successfully.');
    }

    public function banClinic(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:2000',
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        $bannedUntil = null;
        if ($request->filled('days')) {
            $bannedUntil = now()->addDays((int) $request->input('days'));
        }

        ClinicBan::create([
            'clinic_id' => $clinic->id,
            'admin_id' => auth('admin')->id(),
            'reason' => $request->input('reason'),
            'banned_until' => $bannedUntil,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Clinic has been banned.');
    }

    public function unbanClinic($id)
    {
        $clinic = Clinic::findOrFail($id);

        $ban = $clinic->bans()->active()->latest('created_at')->first();

        if ($ban) {
            $ban->status = 'lifted';
            $ban->banned_until = now();
            $ban->save();
        }

        return redirect()->back()->with('success', 'Clinic ban has been lifted.');
    }

    public function viewAppeals()
    {
        $appeals = ClinicBanAppeal::with(['clinic', 'ban'])->latest()->paginate(20);

        return view('admin.appeals', compact('appeals'));
    }

    public function approveAppeal($id)
    {
        $appeal = ClinicBanAppeal::with('ban.clinic')->findOrFail($id);

        $appeal->status = 'approved';
        $appeal->reviewed_by_admin_id = auth('admin')->id();
        $appeal->reviewed_at = now();
        $appeal->save();

        if ($appeal->ban) {
            $appeal->ban->status = 'lifted';
            $appeal->ban->banned_until = now();
            $appeal->ban->save();
        }

        return redirect()->back()->with('success', 'Appeal approved and ban lifted.');
    }

    public function rejectAppeal($id)
    {
        $appeal = ClinicBanAppeal::findOrFail($id);

        $appeal->status = 'rejected';
        $appeal->reviewed_by_admin_id = auth('admin')->id();
        $appeal->reviewed_at = now();
        $appeal->save();

        return redirect()->back()->with('success', 'Appeal rejected.');
    }

    /**
     * ✅ Get Clinic Details (Services & Reviews)
     */
    public function getClinicDetails($id)
    {
        $clinic = Clinic::with(['services', 'reviews.owner'])->findOrFail($id);

        return response()->json([
            'services' => $clinic->services,
            'reviews' => $clinic->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'reviewer_name' => $review->owner ? $review->owner->full_name : 'Anonymous',
                    'date' => $review->created_at->format('M d, Y'),
                ];
            }),
        ]);
    }

    /**
     * 🚪 Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
