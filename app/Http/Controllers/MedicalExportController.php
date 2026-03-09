<?php
namespace App\Http\Controllers;

use App\Models\MedicalExport;
use App\Models\Pet;
use Illuminate\Http\Request;

class MedicalExportController extends Controller
{
    public function index() {
        $medical_exports = MedicalExport::with('pet')->get();
        return view('medical_exports.index', compact('medical_exports'));
    }

    public function create() {
        $pets = Pet::all();
        return view('medical_exports.create', compact('pets'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'export_data' => 'required',
        ]);

        MedicalExport::create($request->all());
        return redirect()->route('medical-exports.index')->with('success', 'Medical export created successfully.');
    }

    public function show($id) {
        $medical_export = MedicalExport::with('pet')->findOrFail($id);
        return view('medical_exports.show', compact('medical_export'));
    }

    public function edit($id) {
        $medical_export = MedicalExport::findOrFail($id);
        $pets = Pet::all();
        return view('medical_exports.edit', compact('medical_export', 'pets'));
    }

    public function update(Request $request, $id) {
        $medical_export = MedicalExport::findOrFail($id);
        $medical_export->update($request->all());
        return redirect()->route('medical_exports.index')->with('success', 'Medical export updated successfully.');
    }

    public function destroy($id) {
        MedicalExport::destroy($id);
        return redirect()->route('medical_exports.index')->with('success', 'Medical export deleted successfully.');
    }
}
