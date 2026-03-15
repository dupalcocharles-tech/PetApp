<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PetOwnerMedicalRecordPdfController extends Controller
{
    public function download(Appointment $appointment, Pet $pet)
    {
        $petOwner = Auth::guard('pet_owner')->user();

        if (!$petOwner || (int) $appointment->pet_owner_id !== (int) $petOwner->id) {
            abort(403);
        }

        if ((int) $pet->pet_owner_id !== (int) $petOwner->id) {
            abort(403);
        }

        if (!$appointment->pets()->whereKey($pet->id)->exists()) {
            abort(404);
        }

        $appointment->loadMissing(['clinic', 'service', 'medicalRecords', 'medications']);

        $clinic = $appointment->clinic;
        $record = $appointment->medicalRecords->firstWhere('pet_id', $pet->id);
        $medications = $appointment->medications->where('pet_id', $pet->id)->values();

        $clinicLogoDataUri = null;
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
        $defaultLogoPath = public_path('images/paw.png');

        $toDataUri = function (string $path) use ($allowedExtensions): ?string {
            if (!is_file($path)) {
                return null;
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions, true)) {
                return null;
            }

            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                default => 'image/png',
            };

            $data = file_get_contents($path);
            if ($data === false) {
                return null;
            }

            return 'data:' . $mime . ';base64,' . base64_encode($data);
        };

        if ($clinic && $clinic->profile_image) {
            $filename = basename((string) $clinic->profile_image);
            $path = base_path('storage/clinics/' . $filename);
            $clinicLogoDataUri = $toDataUri($path);
        }

        if (!$clinicLogoDataUri) {
            $clinicLogoDataUri = $toDataUri($defaultLogoPath);
        }

        $pdf = Pdf::loadView('pdf.medical_record', [
            'appointment' => $appointment,
            'pet' => $pet,
            'clinic' => $clinic,
            'record' => $record,
            'medications' => $medications,
            'clinicLogoDataUri' => $clinicLogoDataUri,
        ])->setPaper('a4')
            ->setOptions([
                'isRemoteEnabled' => true,
            ]);

        $safePet = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $pet->name);
        $safeDate = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $appointment->appointment_date);

        return $pdf->download('medical_record_' . $safePet . '_' . $safeDate . '.pdf');
    }
}

