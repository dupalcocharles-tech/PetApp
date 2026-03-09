<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitNote;
use App\Models\Appointment;

class VisitNoteController extends Controller
{
    public function index()
    {
        // Make sure to eager load appointment and pet
        $visit_notes = VisitNote::with('appointment.pet')->get();

        return view('visit_notes.index', compact('visit_notes'));
    }

    public function create()
    {
        $appointments = Appointment::all();
        return view('visit_notes.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'notes' => 'required|string',
        ]);

        VisitNote::create($request->all());
        return redirect()->route('visit-notes.index')->with('success', 'Visit Note created successfully.');
    }

    public function show($id)
    {
        $visit_note = VisitNote::with('appointment.pet')->findOrFail($id);
        return view('visit_notes.show', compact('visit_note'));
    }

    public function edit($id)
    {
        $visit_note = VisitNote::findOrFail($id);
        $appointments = Appointment::all();
        return view('visit_notes.edit', compact('visit_note', 'appointments'));
    }

    public function update(Request $request, $id)
    {
        $visit_note = VisitNote::findOrFail($id);

        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'notes' => 'required|string',
        ]);

        $visit_note->update($request->all());
        return redirect()->route('visit-notes.index')->with('success', 'Visit Note updated successfully.');
    }

    public function destroy($id)
    {
        VisitNote::destroy($id);
        return redirect()->route('visit-notes.index')->with('success', 'Visit Note deleted successfully.');
    }
}
