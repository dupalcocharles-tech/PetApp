<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Pet;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index() {
        $questionnaires = Questionnaire::with('pet')->get();
        return view('questionnaires.index', compact('questionnaires'));
    }

    public function create() {
        $pets = Pet::all();
        return view('questionnaires.create', compact('pets'));
    }

    public function store(Request $request) {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'questions' => 'required|string',
            'answers' => 'nullable|string',
        ]);

        Questionnaire::create($request->all());
        return redirect()->route('questionnaires.index')->with('success', 'Questionnaire created successfully.');
    }

    public function show($id) {
        $questionnaire = Questionnaire::with('pet')->findOrFail($id);
        return view('questionnaires.show', compact('questionnaire'));
    }

    public function edit($id) {
        $questionnaire = Questionnaire::findOrFail($id);
        $pets = Pet::all();
        return view('questionnaires.edit', compact('questionnaire', 'pets'));
    }

    public function update(Request $request, $id) {
        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->update($request->all());
        return redirect()->route('questionnaires.index')->with('success', 'Questionnaire updated successfully.');
    }

    public function destroy($id) {
        Questionnaire::destroy($id);
        return redirect()->route('questionnaires.index')->with('success', 'Questionnaire deleted successfully.');
    }
}
