@extends('layouts.app')
@section('content')
@php
use Illuminate\Support\Str;
@endphp

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Visit Notes</h2>
        <a href="{{ route('visit-notes.create') }}" class="btn btn-primary">Add Note</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Appointment</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visit_notes as $note)
                <tr>
                    <td>{{ $note->appointment->id }} - {{ $note->appointment->pet->name }}</td>
                    <td>{{ Str::limit($note->notes, 50) }}</td>
                    <td>
                        <a href="{{ route('visit-notes.show', $note->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('visit-notes.edit', $note->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ url('/') }}" class="btn btn-primary">Home</a>

    </div>
</div>
@endsection
