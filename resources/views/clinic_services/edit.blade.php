@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h2>Edit Clinic Service</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('clinic-services.update', $clinicService->id) }}">
                @csrf
                @method('PUT')

                {{-- Clinic --}}
                <div class="mb-3">
                    <label>Clinic:</label>
                    <select class="form-select" name="clinic_id" required>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" 
                                {{ old('clinic_id', $clinicService->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->clinic_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Service --}}
                <div class="mb-3">
                    <label>Service:</label>
                    <select class="form-select" name="service_id" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" 
                                {{ old('service_id', $clinicService->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->service_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Animal Type --}}
                <div class="mb-3">
                    <label>Animal Type:</label>
                    <select class="form-select" name="animal_type" required>
                        @foreach($animalTypes as $type)
                            <option value="{{ $type }}" 
                                {{ old('animal_type', $clinicService->animal_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a href="{{ route('clinic-services.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
