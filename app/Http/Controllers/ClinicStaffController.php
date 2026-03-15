<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Clinic;
use App\Models\Animal;
use App\Models\Service;
use App\Models\Appointment;

class ClinicStaffController extends Controller
{
    /**
     * Show the clinic dashboard
     */
    public function dashboard()
    {
        /** @var \App\Models\Clinic $clinic */
        $clinic = auth('clinic')->user();

        if (!$clinic->is_subscribed || $clinic->subscriptionIsExpired()) {
            return redirect()->route('clinic.subscription')->with('message', 'Your clinic subscription is inactive or has expired. Please renew to access the dashboard.');
        }

        // Load animals and services relations
        $clinic->load('animals', 'services');

        // All appointments for this clinic, with related service and pet
        $appointments = Appointment::with(['service', 'pet', 'owner'])
                            ->where('clinic_id', $clinic->id)
                            ->latest()
                            ->get();

        // Fetch records (Appointments with medical records)
        $records = Appointment::where('clinic_id', $clinic->id)
                            ->whereHas('medicalRecord')
                            ->with(['pet.owner', 'medicalRecord', 'service'])
                            ->latest()
                            ->get();

        // Recent appointments (latest 5)
        $recentAppointments = $appointments->take(5);

        return view('staff.dashboard', compact('clinic', 'appointments', 'recentAppointments', 'records'));
    }

    /**
     * Show clinic profile edit form
     */
    public function edit()
    {
        /** @var \App\Models\Clinic $clinic */
        $clinic = auth('clinic')->user();
        return view('staff.edit', compact('clinic'));
    }

    /**
     * Update clinic profile (with optional password change)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Clinic $clinic */
        $clinic = auth('clinic')->user();

        if (!$clinic) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in first']);
        }

        // Validate input
        $request->validate([
            'clinic_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clinics,email,' . $clinic->id,
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'description' => 'nullable|string',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        // Get basic fields
        $data = $request->only('clinic_name','email','address','phone', 'description');

        // Hash password if filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile image upload
if ($request->hasFile('profile_image')) {
    $file = $request->file('profile_image');
    if ($file->isValid()) {
        // Delete old image if exists
        if ($clinic->profile_image) {
            Storage::disk('public')->delete('clinics/' . $clinic->profile_image);
        }

        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
        $file->move(base_path('storage/clinics'), $filename);
        $data['profile_image'] = $filename;           // save only filename
    }
}

// Handle Gallery Upload
if ($request->hasFile('gallery')) {
    $gallery = $clinic->gallery ?? [];
    foreach ($request->file('gallery') as $image) {
        if ($image->isValid()) {
            $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $image->getClientOriginalName());
            $image->move(base_path('storage/clinics/gallery'), $filename);
            $gallery[] = $filename;
        }
    }
    $data['gallery'] = $gallery;
}

        // Update clinic
        $clinic->fill($data);
        $clinic->save();

        // ⚠️ Logout and force fresh login
        Auth::guard('clinic')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Profile updated. Please login again.');


    }

    /**
     * Update animals & services for the clinic
     */
    public function updateAnimalsServices(Request $request)
    {
        /** @var \App\Models\Clinic $clinic */
        $clinic = auth('clinic')->user();

        // --- Process typed animals ---
        $animalIds = [];
        if ($request->filled('animals_input')) {
            $animalsInput = explode(',', $request->input('animals_input'));
            foreach ($animalsInput as $name) {
                $name = trim($name);
                if ($name) {
                    // Add to animals table if not exists
                    $animal = Animal::firstOrCreate(['name' => $name]);
                    $animalIds[] = $animal->id;
                }
            }
        }

        // Sync pivot table clinic_animal
        $clinic->animals()->sync($animalIds);

        // --- Process typed services ---
        $serviceIds = [];
        if ($request->filled('services_input')) {
            $servicesInput = explode(',', $request->input('services_input'));
            foreach ($servicesInput as $name) {
                $name = trim($name);
                if ($name) {
                    // Add to services table if not exists
                    $service = Service::firstOrCreate(['service_name' => $name]);
                    $serviceIds[] = $service->id;
                }
            }
        }

        // Sync pivot table clinic_service
        $clinic->services()->sync($serviceIds);

        return redirect()->route('clinic.dashboard')
                         ->with('success','Animals & Services updated successfully!');
    }

    /**
     * Update clinic availability settings
     */
    public function updateAvailability(Request $request)
    {
        /** @var \App\Models\Clinic $clinic */
        $clinic = auth('clinic')->user();

        $request->validate([
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'is_24_hours' => 'boolean',
        ]);

        $clinic->update([
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'is_24_hours' => $request->has('is_24_hours'),
        ]);

        return back()->with('success', 'Availability settings updated successfully!');
    }
}
