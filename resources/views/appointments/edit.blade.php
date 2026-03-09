@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Appointment</h1>

    <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pet:</label>
            <select name="pet_id" class="form-select">
                @foreach($pets as $pet)
                    <option value="{{ $pet->id }}" @if($appointment->pet_id == $pet->id) selected @endif>{{ $pet->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Clinic Service:</label>
            <select name="clinic_service_id" class="form-select">
                @foreach($clinic_services as $cs)
                    <option value="{{ $cs->id }}" @if($appointment->clinic_service_id == $cs->id) selected @endif>
                        {{ $cs->service->service_name }} - {{ $cs->clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date:</label>
            <input type="datetime-local" name="appointment_date" class="form-control"
                   value="{{ date('Y-m-d\TH:i', strtotime($appointment->appointment_date)) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-select">
                <option value="pending" @if($appointment->status == 'pending') selected @endif>Pending</option>
                <option value="confirmed" @if($appointment->status == 'confirmed') selected @endif>Confirmed</option>
                <option value="completed" @if($appointment->status == 'completed') selected @endif>Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
