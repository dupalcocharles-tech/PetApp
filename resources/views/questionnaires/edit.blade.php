@extends('layouts.app')
@section('content')
<h1>Edit Questionnaire</h1>
<form method="POST" action="{{ route('questionnaires.update', $questionnaire->id) }}">
@csrf
@method('PUT')
<label>Pet:</label>
<select name="pet_id">
@foreach($pets as $pet)
<option value="{{ $pet->id }}" @if($questionnaire->pet_id == $pet->id) selected @endif>{{ $pet->name }}</option>
@endforeach
</select>
<label>Questions:</label>
<textarea name="questions">{{ $questionnaire->questions }}</textarea>
<label>Answers:</label>
<textarea name="answers">{{ $questionnaire->answers }}</textarea>
<button type="submit">Update</button>
</form>
@endsection
