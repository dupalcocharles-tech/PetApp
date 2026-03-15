<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\PetOwner;
use App\Models\Animal;
use App\Models\Clinic;
use App\Models\Appointment;
use App\Models\ClinicReport;

class PetOwnerController extends Controller
{
    /**
     * -------------------------------
     * Pet Owner Dashboard
     * -------------------------------
     */
    public function dashboard()
    {
        /** @var PetOwner $owner */
        $owner = auth()->guard('pet_owner')->user();

        $pets = $owner->pets()->with('animal')->get();

        $recentAppointments = $owner->appointments()
            ->with(['pets.animal', 'clinic', 'review.owner', 'medicalRecords.pet', 'service', 'medications'])
            ->latest()
            ->take(5)
            ->get();

        $animals = Animal::all();

        $selectedLocation = request('location');

        if ($selectedLocation) {
            $terms = [];
            $terms[] = trim($selectedLocation);

            if (stripos($selectedLocation, 'City') !== false) {
                $terms[] = trim(str_ireplace('City', '', $selectedLocation));
            }

            if (strcasecmp(trim($selectedLocation), 'Cebu City') === 0) {
                $terms[] = 'Cebu';
            }

            $terms = array_values(array_filter(array_unique($terms)));

            $clinics = Clinic::with(['services', 'reviews.owner'])
                ->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('address', 'LIKE', '%' . $term . '%');
                    }
                })
                ->get();
        } else {
            $clinics = collect();
        }

        $clinicsJson = $clinics->map(function ($clinic) {
            $specializations = $clinic->specializations ?? [];

            $latestReviews = $clinic->reviews()->latest()->take(3)->get()->map(function ($review) {
                return [
                    'user_name' => $review->owner->full_name ?? 'Anonymous',
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'images' => $review->images,
                    'created_at' => $review->created_at->format('M d, Y'),
                ];
            });

            return [
                'id' => $clinic->id,
                'clinic_name' => $clinic->clinic_name,
                'address' => $clinic->address,
                'phone' => $clinic->phone,
                'email' => $clinic->email,
                'description' => $clinic->description,
                'gallery' => collect($clinic->gallery ?? [])->map(function ($img) {
                    return asset('storage/clinics/gallery/' . $img);
                })->all(),
                'qr_code' => $clinic->qr_code,
                'specializations' => array_map('trim', $specializations),
                'services' => $clinic->services->map(function ($service) {
                    $images = [];
                    if (is_array($service->images)) {
                        $images = array_map(function ($path) {
                            return asset('storage/' . ltrim($path, '/'));
                        }, $service->images);
                    }

                    $homeSlots = [];
                    if (is_array($service->home_slots)) {
                        $homeSlots = collect($service->home_slots)
                            ->filter(function ($slot) use ($service) {
                                if (empty($slot)) {
                                    return false;
                                }

                                return !Appointment::where('service_id', $service->id)
                                    ->where('appointment_date', $slot)
                                    ->exists();
                            })
                            ->values()
                            ->all();
                    }

                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'service_name' => $service->name,
                        'description' => $service->description,
                        'price' => $service->price,
                        'location_type' => $service->location_type,
                        'animals' => is_string($service->animal_type) ? array_values(array_filter(explode(',', $service->animal_type))) : [],
                        'images' => $images,
                        'is_available' => (bool) $service->is_available,
                        'slots' => $homeSlots,
                    ];
                }),
                'image_url' => $clinic->profile_image
                    ? asset('storage/clinics/' . $clinic->profile_image)
                    : asset('images/default_clinic.png'),
                'avg_rating' => round($clinic->reviews()->avg('rating'), 1),
                'is_active' => $clinic->is_open,
                'opening_time' => $clinic->opening_time,
                'closing_time' => $clinic->closing_time,
                'formatted_opening_time' => $clinic->opening_time ? Carbon::parse($clinic->opening_time)->format('g:i A') : null,
                'formatted_closing_time' => $clinic->closing_time ? Carbon::parse($clinic->closing_time)->format('g:i A') : null,
                'is_24_hours' => $clinic->is_24_hours,
                'reviews' => $latestReviews,
            ];
        });

        $reportNotifications = ClinicReport::with(['clinic.bans'])
            ->where('pet_owner_id', $owner->id)
            ->latest()
            ->get()
            ->map(function ($report) {
                $clinicName = $report->clinic?->clinic_name ?? 'Clinic';
                $status = $report->status;

                $parts = [];

                if ($status === 'reviewed') {
                    $parts[] = 'Your report for ' . $clinicName . ' has been reviewed.';
                } elseif ($status === 'dismissed') {
                    $parts[] = 'Your report for ' . $clinicName . ' has been dismissed.';
                }

                $banMessage = null;
                if ($report->clinic && $report->clinic->bans && $report->clinic->bans->count() > 0) {
                    $ban = $report->clinic->bans->firstWhere('status', 'active');
                    if ($ban) {
                        if ($ban->banned_until) {
                            $totalDays = $ban->banned_until->diffInDays($ban->created_at, false);
                            if ($totalDays > 0) {
                                $banMessage = 'The clinic has been banned for ' . $totalDays . ' day' . ($totalDays === 1 ? '' : 's') . '.';
                            } else {
                                $banMessage = 'The clinic has been banned until ' . $ban->banned_until->format('M d, Y') . '.';
                            }
                        } else {
                            $banMessage = 'The clinic has been banned indefinitely.';
                        }
                    }
                }

                if ($banMessage) {
                    $parts[] = $banMessage;
                }

                $message = trim(implode(' ', $parts));

                if ($message === '') {
                    return null;
                }

                return [
                    'clinic_name' => $clinicName,
                    'message' => $message,
                    'created_at' => $report->created_at,
                ];
            })
            ->filter()
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('user.dashboard', compact(
            'owner',
            'pets',
            'recentAppointments',
            'animals',
            'clinicsJson',
            'selectedLocation',
            'reportNotifications'
        ));
    }


    /**
     * -------------------------------
     * Book an Appointment
     * -------------------------------
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'pet_ids' => 'required|array|min:1',
            'pet_ids.*' => 'exists:pets,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $clinic = Clinic::findOrFail($request->clinic_id);
        
        // Check availability
        if (!$clinic->isOpenAt($request->appointment_date)) {
             return back()->with('error', 'The clinic is closed at the selected time. Please check their operating hours (' . $clinic->opening_time . ' - ' . $clinic->closing_time . ').');
        }

        $appointment = Appointment::create([
            'pet_owner_id' => auth()->guard('pet_owner')->id(),
            'pet_id' => null, // Multiple pets are stored in pivot table
            'clinic_id' => $request->clinic_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'requested',
            // 'notes' => $request->reason, // if you have notes column
        ]);
        
        $appointment->pets()->attach($request->pet_ids);

        return back()->with('success', 'Appointment requested successfully!');
    }

    /**
     * -------------------------------
     * Cancel Appointment
     * -------------------------------
     */
    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('pet_owner_id', auth()->guard('pet_owner')->id())
            ->firstOrFail();

        if (!in_array($appointment->status, ['pending', 'requested'])) {
            return back()->with('error', 'You can only cancel pending or requested appointments.');
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * -------------------------------
     * Show Edit Profile Form
     * -------------------------------
     */
    public function edit()
    {
        /** @var PetOwner $petOwner */
        $petOwner = auth()->guard('pet_owner')->user();
        return view('pet_owners.edit', compact('petOwner')); // ✅ changed to pet_owners
    }

    /**
     * -------------------------------
     * Update Profile (with image upload)
     * -------------------------------
     */
    public function update(Request $request)
    {
        $petOwnerId = Auth::guard('pet_owner')->id();
        $petOwner = PetOwner::find($petOwnerId);

        if (!$petOwner) {
            return redirect()->route('login');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:pet_owners,email,' . $petOwner->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        // Update profile fields
        $petOwner->full_name = $request->full_name;
        $petOwner->email = $request->email;
        $petOwner->phone = $request->phone;
        $petOwner->address = $request->address;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            
            if ($file->isValid()) {
                // Delete old image if exists
                if ($petOwner->profile_image) {
                    Storage::disk('public')->delete($petOwner->profile_image);
                }

                // Store new image with a clear, sanitized filename
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
                
                // Explicitly target the root storage directory
                $file->move(base_path('storage/profile_images'), $filename);
                
                $petOwner->profile_image = 'profile_images/' . $filename;
            }
        }

        // Update password only if provided
        if ($request->filled('password')) {
            $petOwner->password = Hash::make($request->password);
        }

        // Save updated data
        $petOwner->save();

        return redirect()->route('pet_owner.dashboard')
            ->with('success', 'Profile updated successfully!');
    }
}
