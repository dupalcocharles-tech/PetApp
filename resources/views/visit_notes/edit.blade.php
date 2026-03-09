@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Visit Note</h1>

    <form method="POST" action="{{ route('visit-notes.update', $visit_note->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="appointment_id" class="form-label">Appointment</label>
            <select class="form-select" name="appointment_id" id="appointment_id" required>
                @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" @if($visit_note->appointment_id == $appt->id) selected @endif>
                        {{ $appt->pet->name }} - {{ $appt->appointment_date }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" name="notes" id="notes" rows="4" required>{{ $visit_note->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('visit-notes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
