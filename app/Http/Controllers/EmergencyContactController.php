<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use App\Models\PetOwner;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    // Display all emergency contacts
    public function index() {
        $emergency_contacts = EmergencyContact::with('petOwner')->get();
        return view('emergency_contacts.index', compact('emergency_contacts'));
    }

    // Show details of a single contact
    public function show($id) {
        $emergency_contact = EmergencyContact::with('petOwner')->findOrFail($id);
        return view('emergency_contacts.show', compact('emergency_contact'));
    }

    // Edit a contact
    public function edit($id) {
        $emergency_contact = EmergencyContact::findOrFail($id);
        $pet_owners = PetOwner::all();
        return view('emergency_contacts.edit', compact('emergency_contact', 'pet_owners'));
    }

    // Update a contact
    public function update(Request $request, $id) {
        $request->validate([
            'pet_owner_id' => 'required|exists:pet_owners,id',
            'name' => 'required',
            'phone' => 'required',
            'relationship' => 'nullable|string',
        ]);

        $emergency_contact = EmergencyContact::findOrFail($id);
        $emergency_contact->update($request->all());

        return redirect()->route('emergency-contacts.index')
            ->with('success', 'Emergency contact updated successfully.');
    }

    // Delete a contact
    public function destroy($id) {
        EmergencyContact::destroy($id);
        return redirect()->route('emergency-contacts.index')
            ->with('success', 'Emergency contact deleted successfully.');
    }
}
