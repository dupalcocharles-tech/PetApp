@extends('layouts.app')
@section('content')
<h1>Questionnaires</h1>
<a href="{{ route('questionnaires.create') }}">Add Questionnaire</a>
<table>
<thead><tr><th>Pet</th><th>Questions</th><th>Actions</th></tr></thead>
<tbody>
@foreach($questionnaires as $q)
<tr>
<td>{{ $q->pet->name }}</td>
<td>{{ Str::limit($q->questions, 50) }}</td>
<td>
<a href="{{ route('questionnaires.show', $q->id) }}">View</a>
<a href="{{ route('questionnaires.edit', $q->id) }}">Edit</a>
</td>
</tr>
@endforeach
</tbody>
</table>
<a href="{{ url('/') }}" class="btn btn-primary">Home</a>

@endsection
