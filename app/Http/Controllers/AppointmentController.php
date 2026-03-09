<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Clinic;
use App\Models\AppointmentMedicalRecord;
use App\Models\AppointmentMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    // -----------------------------
    // 1️⃣ Clinic Dashboard - View Appointments
    // -----------------------------
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $isExpired = $clinic->subscriptionIsExpired();

        if (!$clinic->is_subscribed) {
            return redirect()->route('clinic.subscription')->with('message', 'Your clinic subscription is inactive or has expired. Please renew to access the dashboard.');
        }

        $showExpiredModal = false;

        if ($isExpired) {
            if (session('subscription_expired_acknowledged')) {
                return redirect()->route('clinic.subscription')->with('message', 'Your clinic subscription has expired. Please renew to access the dashboard.');
            }
            $showExpiredModal = true;
        }

        $appointments = Appointment::with(['pets.animal', 'service', 'owner', 'medicalRecords'])
            ->where('clinic_id', $clinic->id)
            ->latest()
            ->get();

        // Fetch records (Appointments with medical records)
        $records = AppointmentMedicalRecord::whereHas('appointment', function($q) use ($clinic) {
                            $q->where('clinic_id', $clinic->id);
                        })
                        ->with(['appointment.owner', 'appointment.service', 'pet'])
                        ->latest()
                        ->get();

        $recentAppointments = $appointments->take(5);

        return view('staff.dashboard', compact('clinic', 'appointments', 'records', 'recentAppointments', 'showExpiredModal'));
    }

    // -----------------------------
    // 2️⃣ Pet Owner Booking - Store New Appointment
    // -----------------------------
    public function store(Request $request)
    {
        $request->validate([
            'pet_ids'     => 'required|array',
            'pet_ids.*'   => 'exists:pets,id',
            'clinic_id'   => 'required|exists:clinics,id',
            'service_id'  => 'nullable|exists:services,id',
            'appointment_date'  => 'required|date|after_or_equal:today',
            'payment_method' => 'nullable|in:clinic,online',
            'payment_option' => 'nullable|in:full,half',
            'receipt'        => 'required_if:payment_method,online|image|mimes:jpeg,png,jpg,gif|max:5120',
            'service_location' => 'nullable|in:clinic,home',
            'service_address'  => 'nullable|string|max:255',
            'service_contact'  => 'nullable|string|max:50',
        ]);

        // Check Clinic Availability
        $clinic = Clinic::findOrFail($request->clinic_id);
        if (!$clinic->isOpenAt($request->appointment_date)) {
            return back()->with('error', 'The clinic is closed at the selected time. Please check their operating hours (' . $clinic->opening_time . ' - ' . $clinic->closing_time . ').');
        }

        // ------------------------------------------
        // CONFLICT CHECK LOGIC
        // ------------------------------------------
        $appointmentDate = \Carbon\Carbon::parse($request->appointment_date);
        $shouldCheckConflict = true;

        if ($clinic->is_24_hours) {
            $time = $appointmentDate->format('H:i:s');
            // Apply logic only between 6:00 AM and 8:00 PM
            if ($time >= '06:00:00' && $time <= '20:00:00') {
                $shouldCheckConflict = true;
            } else {
                // Past 8 PM until 6 AM: free to be booked
                $shouldCheckConflict = false;
            }
        }

        if ($shouldCheckConflict) {
            // Format date to match DB structure
            $formattedDate = $appointmentDate->format('Y-m-d H:i:s');
            
            $conflict = Appointment::where('clinic_id', $request->clinic_id)
                ->where('appointment_date', $formattedDate)
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->exists();
            
            if ($conflict) {
                return back()->with('error', 'This specific time slot has already been booked by another user. Please select a different time.');
            }
        }

        $petOwner = Auth::guard('pet_owner')->user();

        // Check if any of the pets already have an appointment on this date
        foreach ($request->pet_ids as $pId) {
            $exists = Appointment::where('clinic_id', $request->clinic_id)
                ->whereDate('appointment_date', $request->appointment_date)
                ->whereHas('pets', function($q) use ($pId) {
                    $q->where('pets.id', $pId);
                })
                ->exists();
            
            if ($exists) {
                $petName = Pet::find($pId)->name ?? 'Pet';
                return back()->with('error', "$petName already has an appointment for that date.");
            }
        }

        $appointment = Appointment::create([
            'pet_owner_id' => $petOwner->id,
            'pet_id'       => $request->pet_ids[0] ?? null, // Fallback for backward compatibility
            'clinic_id'    => $request->clinic_id,
            'service_id'   => $request->service_id,
            'service_location' => $request->input('service_location', 'clinic'),
            'service_address'  => $request->input('service_address'),
            'service_contact'  => $request->input('service_contact'),
            'appointment_date' => $request->appointment_date,
            'status'       => 'requested',
            'payment_method' => $request->payment_method ?? 'clinic',
            'payment_option' => $request->payment_method === 'online' ? ($request->payment_option ?? 'half') : null,
        ]);

        // Attach pets to the appointment
        $appointment->pets()->attach($request->pet_ids);

        // Handle Payment Receipt Upload (Immediate)
        if ($request->payment_method === 'online' && $request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('payment_receipts', 'public');
            $appointment->payment_receipt = $path;
            
            // Automatically set payment status based on option
            $paymentOption = $request->payment_option ?? 'half';
            $appointment->payment_status = ($paymentOption === 'full') ? 'paid' : 'downpayment_paid';
            
            $appointment->save();
        }

        // No longer redirect to PayMongo immediately. Payment will be handled via receipt upload after approval.
        /*
        if ($request->payment_method === 'online') {
            return redirect()->route('payment.checkout', ['appointment' => $appointment->id]);
        }
        */

        return back()->with('success', 'Appointment request sent! Waiting for clinic approval.');
    }

    // -----------------------------
    // 3️⃣ Clinic - Accept Appointment
    // -----------------------------
    public function accept($id)
    {
        $clinic = Auth::guard('clinic')->user();
        $appointment = Appointment::with('owner')->findOrFail($id);

        if ($appointment->clinic_id !== $clinic->id) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->update(['status' => 'approved']);

        // Send SMS Notification
        if ($appointment->owner && $appointment->owner->phone) {
            $message = "Hello! Your appointment request at {$clinic->clinic_name} has been APPROVED. See you on " . \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y g:i A') . ".";
            $this->sendSms($appointment->owner->phone, $message);
        }

        return back()->with('success', 'Appointment accepted and approved!');
    }

    // -----------------------------
    // 4️⃣ Clinic - Update Appointment Status
    // -----------------------------
    public function update(Request $request, $id)
    {
        $appointment = Appointment::with('owner')->findOrFail($id);
        $clinic = Auth::guard('clinic')->user();

        if ($appointment->clinic_id !== $clinic->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,cancelled,completed',
        ]);

        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        $appointment->update([
            'status' => $newStatus,
        ]);

        // Send SMS Notification for specific changes
        if ($appointment->owner && $appointment->owner->phone) {
            $message = "";
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $message = "Hello! Your appointment at {$clinic->clinic_name} has been APPROVED for " . \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y g:i A') . ".";
            } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $message = "Hello. We regret to inform you that your appointment at {$clinic->clinic_name} on " . \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') . " has been CANCELLED.";
            } elseif ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $message = "Hello! Your appointment at {$clinic->clinic_name} is now COMPLETED. Thank you for choosing us!";
            }

            if ($message) {
                $this->sendSms($appointment->owner->phone, $message);
            }
        }

        return back()->with('success', 'Appointment status updated!');
    }

    // -----------------------------
    // 5️⃣ Show Appointment Details
    // -----------------------------
    public function show($id)
    {
        $appointment = Appointment::with(['pet', 'service', 'clinic', 'owner'])
            ->findOrFail($id);

        return view('appointments.show', compact('appointment'));
    }

    // -----------------------------
    // 6️⃣ Delete Appointment
    // -----------------------------
    public function destroy($id)
    {
        $clinic = Auth::guard('clinic')->user();
        $appointment = Appointment::findOrFail($id);

        if ($appointment->clinic_id !== $clinic->id) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully!');
    }

    // -----------------------------
    // 7️⃣ Complete Appointment
    // -----------------------------
    public function complete(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'pet_id' => 'required|exists:pets,id',
            'weight' => 'nullable|numeric',
            'vaccine_status' => 'nullable|string',
            'vaccination_dates' => 'nullable|string',
            'health_condition' => 'nullable|string',
            'vet_notes' => 'nullable|string',
            'medicine' => 'nullable|array',
            'administration' => 'nullable|array',
            'frequency' => 'nullable|array',
            'duration' => 'nullable|array',
        ]);

        $appointment = Appointment::with(['pets', 'owner'])->findOrFail($request->appointment_id);
        $clinic = Auth::guard('clinic')->user();

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if (!$appointment->pets->contains($request->pet_id) && $appointment->pet_id != $request->pet_id) {
            return response()->json([
                'success' => false,
                'message' => 'Pet does not belong to this appointment.'
            ], 422);
        }

        // Save medical record
        AppointmentMedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id, 'pet_id' => $request->pet_id],
            [
                'weight' => $request->weight,
                'vaccine_status' => $request->vaccine_status,
                'vaccination_dates' => $request->vaccination_dates,
                'health_condition' => $request->health_condition,
                'vet_notes' => $request->vet_notes,
                'vet_id' => $clinic->id
            ]
        );

        // Save medicines if provided
        if ($request->medicine && is_array($request->medicine)) {
            foreach ($request->medicine as $i => $medicine) {
                if (empty($medicine)) continue;

                AppointmentMedication::create([
                    'appointment_id' => $appointment->id,
                    'pet_id' => $request->pet_id,
                    'medicine_name' => $medicine,
                    'dosage' => $request->administration[$i] ?? null,
                    'schedule' => $request->frequency[$i] ?? null,
                    'vet_id' => $clinic->id,
                    'progress' => null
                ]);
            }
        }

        // Check if all pets in the appointment have medical records
        $totalPets = $appointment->pets()->count();
        if ($totalPets == 0 && $appointment->pet_id) {
            $totalPets = 1;
        }
        
        $completedPets = AppointmentMedicalRecord::where('appointment_id', $appointment->id)->count();

        // Update appointment status AND save next appointment info
        $updateData = [
            'next_appointment' => $request->next_appointment ?? null,
            'next_notes'       => $request->next_notes ?? null,
        ];

        $oldStatus = $appointment->status;
        if ($completedPets >= $totalPets) {
            $updateData['status'] = 'completed';
        }

        $appointment->update($updateData);

        // Send SMS if completed now
        if ($appointment->status === 'completed' && $oldStatus !== 'completed') {
            if ($appointment->owner && $appointment->owner->phone) {
                $message = "Hello! Your appointment at {$clinic->clinic_name} is now COMPLETED. Thank you for choosing us!";
                $this->sendSms($appointment->owner->phone, $message);
            }
        }

        return response()->json([
            'success' => true,
            'status' => $appointment->fresh()->status,
            'message' => 'Appointment completed successfully!'
        ]);
    }

    private function sendSms($phone, $message)
    {
        // Normalize PH phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        } elseif (str_starts_with($phone, '9')) {
            $phone = '63' . $phone;
        }

        try {
            $apiToken = '72ae7e3a8c3ed0e97111f3b5a81887f4ae96a344';
            Http::withoutVerifying()
                ->timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token'    => $apiToken,
                    'phone_number' => $phone,
                    'message'      => $message,
                ]);
        } catch (\Exception $e) {
            Log::error('SMS Appointment Notification Error: ' . $e->getMessage());
        }
    }

    // -----------------------------
    // 8️⃣ Completed Appointments
    // -----------------------------
    public function completedAppointments()
    {
        $clinic = auth()->guard('clinic')->user();
        if (!$clinic->is_subscribed) {
            return redirect()->route('clinic.subscription');
        }

        // Fetch completed appointments with pet owner, medical record, and medications
        $appointments = Appointment::where('clinic_id', $clinic->id)
            ->where('status', 'completed')
            ->with([
                'pets',
                'owner',
                'medicalRecords',
                'medications',
                'service'
            ])
            ->get();

        return view('clinic.completed_appointments', compact('clinic', 'appointments'));
    }

    public function nextAppointments()
    {
        $clinic = Auth::guard('clinic')->user();

        if (!$clinic->is_subscribed || $clinic->subscriptionIsExpired()) {
            return redirect()->route('clinic.subscription')->with('message', 'Your clinic subscription is inactive or has expired. Please renew to access the dashboard.');
        }

        $appointments = Appointment::where('clinic_id', $clinic->id)
            ->whereNotNull('next_appointment')
            ->with([
                'pets',
                'owner',
                'medicalRecords',
                'medications',
                'service'
            ])
            ->orderBy('next_appointment', 'asc')
            ->get();

        return view('clinic.completed_appointments', compact('clinic', 'appointments'));
    }


    // -----------------------------
    // 9️⃣ Get Pet Owner History (AJAX)
    // -----------------------------
    public function getPetOwnerHistory($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $appointments = Appointment::with(['pets', 'service', 'medicalRecords.pet'])
            ->where('clinic_id', $clinic->id)
            ->whereHas('pets.owner', function($query) use ($id) {
                $query->where('id', $id);
            })
            ->latest()
            ->get()
            ->map(function ($appointment) {
                // Get pet names
                $petNames = $appointment->pets->pluck('name')->join(', ');
                
                // Get vet notes from all medical records
                $notes = $appointment->medicalRecords->map(function($record) {
                    $petName = e($record->pet ? $record->pet->name : 'Unknown Pet');
                    $note = e($record->vet_notes);
                    return $note ? "<strong>{$petName}:</strong> {$note}" : null;
                })->filter()->join('<br>');

                if (empty($notes)) {
                    $notes = 'No notes available';
                }

                return [
                    'id' => $appointment->id,
                    'date' => $appointment->appointment_date,
                    'pet_name' => $petNames ?: 'Unknown',
                    'service' => $appointment->service ? $appointment->service->name : 'N/A',
                    'status' => ucfirst($appointment->status),
                    'notes' => $notes
                ];
            });

        return response()->json($appointments);
    }
}
