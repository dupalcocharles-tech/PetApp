@extends('layouts.app')
@section('content')
<h1>Questionnaire Details</h1>
<p>Pet: {{ $questionnaire->pet->name }}</p>
<p>Questions: {{ $questionnaire->questions }}</p>
<p>Answers: {{ $questionnaire->answers }}</p>
<a href="{{ route('questionnaires.index') }}">Back</a>
@endsection
