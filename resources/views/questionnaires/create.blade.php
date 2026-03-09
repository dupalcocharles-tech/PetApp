@extends('layouts.app')
@section('content')
<h1>Add Questionnaire</h1>
<form method="POST" action="{{ route('questionnaires.store') }}">
@csrf
<label>Pet:</label>
<select name="pet_id">
@foreach($pets as $pet)
<option value="{{ $pet->id }}">{{ $pet->name }}</option>
@endforeach
</select>
<label>Questions:</label>
<textarea name="questions"></textarea>
<label>Answers (optional):</label>
<textarea name="answers"></textarea>
<button type="submit">Save</button>
</form>
@endsection
