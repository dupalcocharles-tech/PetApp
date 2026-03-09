<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Clinic;

class AnimalController extends Controller
{
    /**
     * Return clinics that provide services for a given animal.
     *
     * @param int $id Animal ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function clinics($id)
    {
        // Find the animal
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json([
                'success' => false,
                'message' => 'Animal not found'
            ], 404);
        }

        // Get clinics offering services for this animal
        $clinics = Clinic::whereHas('clinicServices', function ($query) use ($id) {
            $query->where('animal_id', $id);
        })->get();

        if ($clinics->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No clinics available for this animal',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $clinics
        ]);
    }
}
