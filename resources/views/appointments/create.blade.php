@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Add Appointment</h1>

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="pet_id" class="form-label">Pet</label>
            <select name="pet_id" id="pet_id" class="form-select" required>
                @foreach($pets as $pet)
                    <option value="{{ $pet->id }}">{{ $pet->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="clinic_service_id" class="form-label">Clinic Service</label>
            <select name="clinic_service_id" id="clinic_service_id" class="form-select" required>
                @foreach($clinic_services as $cs)
                    <option value="{{ $cs->id }}">{{ $cs->service->service_name }} - {{ $cs->clinic->clinic_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="appointment_date" class="form-label">Date</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
