@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Visit Note Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Appointment</h5>
            <p class="card-text">{{ $visit_note->appointment->pet->name }} - {{ $visit_note->appointment->appointment_date }}</p>

            <h5 class="card-title">Notes</h5>
            <p class="card-text">{{ $visit_note->notes }}</p>

            <a href="{{ route('visit-notes.index') }}" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</div>
@endsection
