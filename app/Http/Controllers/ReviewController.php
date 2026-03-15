<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicReview;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created clinic review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'clinic_id'      => 'required|exists:clinics,id',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'nullable|string',
            'images'         => 'nullable|array|max:5',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prevent duplicate review per appointment
        if (ClinicReview::where('appointment_id', $request->appointment_id)->exists()) {
            return back()->with('error', 'You already reviewed this appointment.');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = uniqid() . '_' . $image->getClientOriginalName();
                $image->storeAs('reviews', $filename, 'public');
                $imagePaths[] = $filename;
            }
        }

        // Create review (matches clinic_reviews table exactly)
        ClinicReview::create([
            'clinic_id'      => $request->clinic_id,
            'appointment_id' => $request->appointment_id,
            'pet_owner_id'   => Auth::guard('pet_owner')->id(), // ✅ Use pet_owner auth guard
            'rating'         => $request->rating,
            'review'         => $request->review,
            'images'         => $imagePaths,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}
