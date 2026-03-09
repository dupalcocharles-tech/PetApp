@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Add Visit Note</h1>

    <form method="POST" action="{{ route('visit-notes.store') }}">
        @csrf

        <div class="mb-3">
            <label for="appointment_id" class="form-label">Appointment</label>
            <select name="appointment_id" id="appointment_id" class="form-select" required>
                <option value="" selected disabled>Select Appointment</option>
                @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}">{{ $appt->pet->name }} - {{ $appt->appointment_date }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('visit-notes.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
