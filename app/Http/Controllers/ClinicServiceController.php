<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicService;
use App\Models\Clinic;
use App\Models\Service;
use App\Models\Animal;

class ClinicServiceController extends Controller
{
    public function index()
    {
        $clinicServices = ClinicService::with(['clinic', 'service', 'animal'])->get();
        return view('clinic_services.index', compact('clinicServices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'clinic_id'  => 'required|exists:clinics,id',
            'service_id' => 'required|exists:services,id',
            'animal_id'  => 'required|exists:animals,id',
            'price'      => 'nullable|numeric|min:0', // ✅ added validation
        ]);

        ClinicService::create($request->only('clinic_id', 'service_id', 'animal_id', 'price')); // ✅ include price
        return back()->with('success', 'Service added!');
    }

    // ✅ For AJAX/API use
    public function getServicesByClinic($clinicId)
    {
        $clinicServices = ClinicService::with(['service', 'animal'])
            ->where('clinic_id', $clinicId)
            ->get();

        return response()->json($clinicServices);
    }
}
