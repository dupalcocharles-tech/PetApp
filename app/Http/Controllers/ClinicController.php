<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Clinic;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Animal;
use App\Models\Appointment;
use App\Models\ClinicBan;
use App\Models\ClinicBanAppeal;
use Twilio\Rest\Client;


class ClinicController extends Controller
{
    /**
     * Show clinic dashboard
     */
    public function dashboard()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $allAnimals = Animal::all();
        $clinicAnimals = []; // ✅ services table has no animal_type
        $appointments = $clinic->appointments()
                       ->with(['pet.animal', 'service']) // ← add 'service' here
                       ->get();

        // Fetch records (Appointments with medical records)
        $records = Appointment::where('clinic_id', $clinic->id)
                            ->whereHas('medicalRecord')
                            ->with(['pet.owner', 'medicalRecord', 'service'])
                            ->latest()
                            ->get();

        $recentAppointments = $appointments->take(5);


        // ✅ Build JSON for Pet Owner dashboard
        $clinics = Clinic::with(['services'])
            ->get()
            ->map(function($clinic) {
                return [
                    'id' => $clinic->id,
                    'name' => $clinic->clinic_name,
                    'address' => $clinic->address,
                    'image_url' => $clinic->profile_image ? asset('storage/clinics/'.$clinic->profile_image) : asset('images/clinics/default.png'),
                    'avg_rating' => $clinic->average_rating,
                    'is_active' => $clinic->is_open, // Accessor
                    'opening_time' => $clinic->opening_time,
                    'closing_time' => $clinic->closing_time,
                    'is_24_hours' => $clinic->is_24_hours,
                    'specializations' => $clinic->specializations ?? [], // safe default
                    'services' => $clinic->services->map(function($service) {
                        return [
                            'id' => $service->id,
                            'service_name' => $service->name,
                            'description' => $service->description,
                            'price' => $service->price,
                        ];
                    }),
                ];
            });

        $clinicsJson = $clinics->toJson();

        return view('staff.dashboard', compact('clinic', 'allAnimals', 'clinicAnimals', 'appointments', 'clinicsJson', 'records', 'recentAppointments'));
    }

    public function subscription(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $admin = Admin::first();

        if ($request->query('expired_modal') === '1') {
            $request->session()->put('subscription_expired_acknowledged', true);
        }

        return view('clinic.subscription', compact('clinic', 'admin'));
    }

    public function submitSubscription(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        $request->validate([
            'receipt' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if ($clinic->subscription_receipt && Storage::disk('public')->exists('clinics/subscription_receipts/' . $clinic->subscription_receipt)) {
            Storage::disk('public')->delete('clinics/subscription_receipts/' . $clinic->subscription_receipt);
        }

        $file = $request->file('receipt');
        $filename = time() . '_subscription_' . $file->getClientOriginalName();
        $file->storeAs('clinics/subscription_receipts', $filename, 'public');

        $clinic->subscription_receipt = $filename;
        $clinic->is_subscribed = false;
        $clinic->save();

        return back()->with('success', 'Subscription receipt submitted. Please wait for admin approval.');
    }

    /**
     * Show clinic profile
     */
    public function profile()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        return view('clinic.profile', compact('clinic'));
    }

    /**
     * Edit clinic profile
     */
    public function edit()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        return view('staff.edit', compact('clinic'));
    }

    /**
     * Show clinic reviews
     */
    public function reviews()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        
        $reviews = $clinic->reviews()
            ->with('owner')
            ->latest()
            ->paginate(9);

        return view('clinic.reviews', compact('clinic', 'reviews'));
    }

    /**
     * Show clinic records (Pets with medical history)
     */
    public function records(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $search = $request->input('search');

        $query = \App\Models\Pet::whereHas('appointments', function($q) use ($clinic) {
                $q->where('clinic_id', $clinic->id)
                  ->whereHas('medicalRecord');
            });

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $pets = $query->with(['owner', 'appointments' => function($q) use ($clinic) {
                $q->where('clinic_id', $clinic->id)
                  ->whereHas('medicalRecord')
                  ->with(['medicalRecord', 'service'])
                  ->latest();
            }])
            ->paginate(10);

        return view('clinic.records', compact('clinic', 'pets', 'search'));
    }

    /**
     * Update clinic profile
     */
   /**
 * Update clinic profile
 */
public function update(Request $request)
{
    /** @var Clinic $clinic */
    $clinic = auth('clinic')->user();

    // Validate input
    $request->validate([
        'clinic_name' => 'required|string|max:255',
        'email' => 'required|email|unique:clinics,email,' . $clinic->id,
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'password' => 'nullable|string|min:6|confirmed',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        'qr_code' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        'description' => 'nullable|string',
        'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
    ]);

    // Get fields from form
    $data = $request->only([
        'username',
        'email',
        'clinic_name',
        'address',
        'phone',
    ]);

    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        if ($file->isValid()) {
            // Delete old image if exists
            if ($clinic->profile_image) {
                Storage::disk('public')->delete('clinics/' . $clinic->profile_image);
            }

            // Store new image with a clear, sanitized filename
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            $file->move(base_path('storage/clinics'), $filename);

            // Save **only the filename** in the database
            $data['profile_image'] = $filename;
        }
    }

    // ✅ Handle QR Code Upload
    if ($request->hasFile('qr_code')) {
        $file = $request->file('qr_code');
        if ($file->isValid()) {
            // Delete old QR code if exists
            if ($clinic->qr_code) {
                Storage::disk('public')->delete('clinics/qr_codes/' . $clinic->qr_code);
            }

            // Store new QR code
            $filename = time() . '_qr_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            $file->move(base_path('storage/clinics/qr_codes'), $filename);

            $data['qr_code'] = $filename;
        }
    }

    // ✅ Update Description (Bio)
    if ($request->has('description')) {
        $data['description'] = $request->input('description');
    }

    // ✅ Handle Gallery Upload (Append to existing)
    if ($request->hasFile('gallery')) {
        $gallery = $clinic->gallery ?? [];
        foreach ($request->file('gallery') as $image) {
            $filename = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('clinics/gallery', $filename, 'public');
            $gallery[] = $filename;
        }
        $data['gallery'] = $gallery;
    }


    // Save specializations (Model casts to array, so no manual json_encode needed)
    if ($request->has('specializations')) {
        $data['specializations'] = $request->input('specializations');
    } else {
        $data['specializations'] = [];
    }

    // ✅ Optional password update
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    $clinic->update($data);
    $clinic = Clinic::find($clinic->id);

    if ($request->filled('password')) {
        Auth::guard('clinic')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Password updated. Please login again.');
    }

    return redirect()->route('clinic.profile')
                     ->with('success', 'Profile updated successfully.');
}
    /**
     * List all services offered by the clinic
     */
    public function services()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $services = $clinic->services()->get();
        return view('clinic.services.index', compact('services'));
    }

    /**
     * List all animals the clinic specializes in
     * (currently from specializations JSON only)
     */
    public function animals()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $animals = $clinic->specializations ?? [];
        return view('clinic.animals.index', compact('animals'));
    }

    public function banned()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        $ban = $clinic->bans()->active()->latest('created_at')->first();

        if (!$ban) {
            return redirect()->route('clinic.dashboard');
        }

        $liftDate = $ban->banned_until;
        $daysRemaining = $ban->daysRemaining();

        return view('clinic.banned', compact('clinic', 'ban', 'liftDate', 'daysRemaining'));
    }

    public function submitBanAppeal(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        $request->validate([
            'ban_id' => 'required|exists:clinic_bans,id',
            'message' => 'required|string|max:2000',
        ]);

        $ban = ClinicBan::where('id', $request->input('ban_id'))
            ->where('clinic_id', $clinic->id)
            ->firstOrFail();

        $existing = ClinicBanAppeal::where('clinic_ban_id', $ban->id)
            ->where('clinic_id', $clinic->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->route('clinic.banned')->with('error', 'You already submitted an appeal for this ban.');
        }

        ClinicBanAppeal::create([
            'clinic_ban_id' => $ban->id,
            'clinic_id' => $clinic->id,
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        return redirect()->route('clinic.banned')->with('success', 'Your appeal has been submitted for review.');
    }

    /**
     * List all appointments for this clinic
     */
    public function appointments()
{
    /** @var Clinic $clinic */
    $clinic = auth('clinic')->user();

    // include 'service' relationship
    $appointments = $clinic->appointments()->with(['pet.animal', 'service'])->get();

    return view('clinic.appointments.index', compact('appointments'));
}


    /**
     * Get services for a clinic (AJAX)
     */
    public function getServicesByClinic($clinicId)
    {
        $services = Service::where('clinic_id', $clinicId)->get();
        return response()->json($services);
    }

    // --------------------
    // Show form to select animals the clinic serves
    // --------------------
    public function editAnimals()
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();
        $allAnimals = Animal::all();
        $clinicAnimals = $clinic->specializations ?? [];
        return view('clinic.animals.edit', compact('clinic', 'allAnimals', 'clinicAnimals'));
    }

    // --------------------
    // ✅ Update selected animals (stored in specializations JSON only)
    // --------------------
    public function updateAnimals(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        $selectedAnimalTypes = $request->input('specializations', []);

        // Save selected animals in the specializations column
        $clinic->specializations = $selectedAnimalTypes;
        $clinic->save();

        return redirect()->route('clinic.dashboard')->with('success', 'Animals updated.');
    }

    // --------------------
    // Filter clinics by animal specialization
    // --------------------
    public function clinicsByAnimal(Request $request)
    {
        $animalName = $request->query('animal');
        $clinics = Clinic::whereJsonContains('specializations', $animalName)->get();

        return view('clinics.index', compact('clinics', 'animalName'));
    }

    // --------------------
    // ✅ Accept Appointment Request (NEW)
    // --------------------
    public function acceptAppointment($id)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        $appointment = Appointment::where('id', $id)
            ->where('clinic_id', $clinic->id)
            ->firstOrFail();

        if ($appointment->status !== 'requested') {
            return back()->with('error', 'This appointment has already been processed.');
        }

        $appointment->update(['status' => 'pending']);

        return back()->with('success', 'Appointment accepted and marked as pending.');
    }

    public function completeAppointment(Request $request)
{
    $appointment = Appointment::findOrFail($request->appointment_id);

    $appointment->weight = $request->weight ?? $appointment->weight;
    $appointment->vaccine_status = $request->vaccine_status ?? $appointment->vaccine_status;
    $appointment->vaccination_dates = $request->vaccination_dates ?? $appointment->vaccination_dates;
    $appointment->health_condition = $request->health_condition ?? $appointment->health_condition;
    $appointment->vet_notes = $request->vet_notes ?? $appointment->vet_notes;

    // ✅ Save next appointment info correctly
    $appointment->next_appointment = $request->next_appointment ?? null; // text
$appointment->next_notes = $request->next_notes ?? null;


    $appointment->status = 'completed';
    $appointment->save();

    if ($request->has('medicine')) {
        foreach ($request->medicine as $i => $med) {
            $appointment->prescriptions()->create([
                'medicine'       => $med,
                'administration' => $request->administration[$i] ?? '',
                'frequency'      => $request->frequency[$i] ?? '',
                'duration'       => $request->duration[$i] ?? ''
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Appointment completed and next appointment saved.'
    ]);

}


public function sendReminder(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id'
        ]);

        $appointment = Appointment::with(['pets', 'owner'])->find($request->appointment_id);

        if (!$appointment || !$appointment->owner || !$appointment->owner->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Missing owner contact details.'
            ], 400);
        }

        $ownerName = $appointment->owner->full_name;
        $phone = $appointment->owner->phone;

        // Get pet names
        $petNames = $appointment->pets->pluck('name')->join(', ');
        if (empty($petNames)) {
            if ($appointment->pet_id && $pet = \App\Models\Pet::find($appointment->pet_id)) {
                 $petNames = $pet->name;
            } else {
                 $petNames = 'your pet';
            }
        }

        // Normalize PH phone number for PhilSMS (format: 639xxxxxxxxx)
        $phone = preg_replace('/[^0-9]/', '', $phone); // Remove + and spaces
        if (str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        } elseif (str_starts_with($phone, '9')) { // Case where 0 is missing
            $phone = '63' . $phone;
        }

        $nextDate  = $appointment->next_appointment;
        if (!$nextDate) {
             return response()->json([
                'success' => false,
                'message' => 'No next appointment date set for this record.'
            ], 400);
        }

        $message = "Hello {$ownerName}! This is a reminder from your veterinary clinic. "
                 . "{$petNames} has an upcoming appointment on {$nextDate}. "
                 . "Please contact us if you have questions.";

        try {
            // User requested endpoint: https://www.iprogsms.com/api/v1/sms_messages
            $apiToken = '72ae7e3a8c3ed0e97111f3b5a81887f4ae96a344';
            
            // Set timeout to 60 seconds and disable SSL verification
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token'    => $apiToken,
                    'phone_number' => $phone,
                    'message'      => $message,
                ]);

            // Log the response for debugging
            \Illuminate\Support\Facades\Log::info('iProgSMS Response: ' . $response->body());

            $responseBody = $response->json();

            // Check if HTTP request was successful AND API status is not an error
            // iProgSMS sometimes returns HTTP 200 but with "status": 500 in body
            $apiStatus = 200;
            if (is_array($responseBody) && isset($responseBody['status'])) {
                $apiStatus = $responseBody['status'];
            }
            
            if ($response->successful() && $apiStatus == 200) {
                $appointment->reminder_sent = true;
                $appointment->save();

                return response()->json([
                    'success' => true,
                    'message' => 'SMS reminder sent successfully!'
                ]);
            } else {
                // Parse specific error message if available
                $errorMessage = 'SMS Provider Error: ' . $response->body();
                
                if (isset($responseBody['message'])) {
                     $msg = $responseBody['message'];
                     $errorMessage = is_array($msg) ? implode(' ', $msg) : $msg;
                }

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'System Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update clinic password
     */
    public function updatePassword(Request $request)
    {
        /** @var Clinic $clinic */
        $clinic = auth('clinic')->user();

        // Validate password input
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Update password
        $clinic->password = bcrypt($request->password);
        $clinic->save();

        // Logout after password update
        Auth::guard('clinic')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // new
        return redirect()->route('home')
                         ->with('success', 'Password updated. Please login again.');

    }
}
