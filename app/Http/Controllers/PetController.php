<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function index() {
        if (Auth::guard('pet_owner')->check()) {
            // Show only logged-in owner's pets
            $pets = Pet::where('pet_owner_id', Auth::guard('pet_owner')->id())->with('owner')->get();
        } else {
            // Admin or others can see all (or handle accordingly)
            $pets = Pet::with('owner')->get(); 
        }
        
        return view('pets.index', compact('pets'));
    }

    public function create() {
        $owners = PetOwner::all();
        return view('pets.create', compact('owners'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_owner_id' => 'required|exists:pet_owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|max:50',
            'pet_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['pet_owner_id','name','species','breed','age','gender']);

        // Handle pet image upload
        if ($request->hasFile('pet_image')) {
            $path = $request->file('pet_image')->store('pets', 'public');
            $data['image'] = $path;
        }

        Pet::create($data);

        return redirect()->route('pets.index')->with('success', 'Pet created successfully.');
    }

    public function show($id) {
        $pet = Pet::with('owner')->findOrFail($id);
        return view('pets.show', compact('pet'));
    }

    public function edit($id) {
        $pet = Pet::findOrFail($id);
        $owners = PetOwner::all();
        return view('pets.edit', compact('pet', 'owners'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'pet_owner_id' => 'required|exists:pet_owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|max:50',
            'pet_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pet = Pet::findOrFail($id);

        $data = $request->only(['pet_owner_id','name','species','breed','age','gender']);

        // Handle pet image upload
        if ($request->hasFile('pet_image')) {
            // Delete old image if exists
            if ($pet->image && Storage::disk('public')->exists($pet->image)) {
                Storage::disk('public')->delete($pet->image);
            }

            $path = $request->file('pet_image')->store('pets', 'public');
            $data['image'] = $path;
        }

        $pet->update($data);

        return redirect()->route('pets.index')->with('success', 'Pet updated successfully.');
    }

    public function destroy($id) {
        $pet = Pet::findOrFail($id);

        // Delete image if exists
        if ($pet->image && Storage::disk('public')->exists($pet->image)) {
            Storage::disk('public')->delete($pet->image);
        }

        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet deleted successfully.');
    }
}
