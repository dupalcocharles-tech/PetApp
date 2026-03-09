<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\PetOwner;
use App\Models\Appointment;

class AdminVerifyClinicController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        // --------------------
        // Quick stats
        // --------------------
        $totalClinics = Clinic::count();
        $verifiedClinics = Clinic::where('is_verified', true)->count();
        $unverifiedClinics = $totalClinics - $verifiedClinics; // more efficient
        $totalPetOwners = PetOwner::count();
        $upcomingAppointments = Appointment::where('appointment_date', '>=', now())->count();

        // --------------------
        // Pending clinics for verification
        // --------------------
        $clinics = Clinic::where('is_verified', false)->get();

        // --------------------
        // Chart data: Appointments per clinic
        // --------------------
        // Eager load clinic to avoid N+1 queries
        $appointmentsPerClinic = Appointment::selectRaw('clinic_id, COUNT(*) as total')
            ->groupBy('clinic_id')
            ->with('clinic')
            ->get();

        $appointmentsPerClinicLabels = $appointmentsPerClinic->map(function($a) {
            return $a->clinic->clinic_name ?? 'Unknown';
        })->toArray();

        $appointmentsPerClinicData = $appointmentsPerClinic->pluck('total')->toArray();

        // --------------------
        // Upcoming appointments list (optional)
        // --------------------
        $upcomingAppointmentsList = Appointment::where('appointment_date', '>=', now())
            ->with(['clinic', 'petOwner', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        // --------------------
        // Return view with all data
        // --------------------
        return view('admin.dashboard', compact(
            'totalClinics',
            'verifiedClinics',
            'unverifiedClinics',
            'totalPetOwners',
            'upcomingAppointments',
            'clinics',
            'appointmentsPerClinicLabels',
            'appointmentsPerClinicData',
            'upcomingAppointmentsList'
        ));
    }

    /**
     * Verify a clinic
     */
    public function verify($id)
    {
        $clinic = Clinic::findOrFail($id);
        if (!$clinic->subscription_receipt) {
            return back()->with('error', 'Clinic cannot be verified until subscription payment proof is uploaded.');
        }

        $clinic->update(['is_verified' => true]);

        return back()->with('success', 'Clinic verified successfully!');
    }

    /**
     * Optional: redirect index to dashboard
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }
}
