<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Appointment;
use App\Models\ClinicReport;

class ClinicReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'report_message' => 'required|string|max:2000',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $owner = Auth::guard('pet_owner')->user();

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('pet_owner_id', $owner->id)
            ->firstOrFail();

        $existing = ClinicReport::where('appointment_id', $appointment->id)
            ->where('pet_owner_id', $owner->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You already submitted a report for this appointment.');
        }

        $proofPath = null;

        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('clinic_reports', $filename, 'public');
            $proofPath = $filename;
        }

        ClinicReport::create([
            'appointment_id' => $appointment->id,
            'pet_owner_id' => $owner->id,
            'clinic_id' => $appointment->clinic_id,
            'report_message' => $request->report_message,
            'proof_image' => $proofPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your report has been submitted for review.');
    }
}

