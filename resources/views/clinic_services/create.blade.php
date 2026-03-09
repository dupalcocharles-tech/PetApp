@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h2>{{ isset($clinicService) ? 'Edit Clinic Service' : 'Add Clinic Service' }}</h2>
        </div>
        <div class="card-body">
            <form method="POST" 
                  action="{{ isset($clinicService) ? route('clinic-services.update', $clinicService->id) : route('clinic-services.store') }}">
                @csrf
                @if(isset($clinicService))
                    @method('PUT')
                @endif

                {{-- Clinic --}}
                <div class="mb-3">
                    <label>Clinic:</label>
                    <select class="form-select" id="clinicSelect" name="clinic_id" required>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" 
                                {{ (isset($clinicService) && $clinicService->clinic_id == $clinic->id) ? 'selected' : '' }}>
                                {{ $clinic->clinic_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Service --}}
                <div class="mb-3">
                    <label>Service:</label>
                    <select class="form-select" id="serviceSelect" name="service_id" required>
                        @if(isset($clinicService))
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" 
                                    {{ $clinicService->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Select a service</option>
                        @endif
                    </select>
                </div>

                {{-- Animal Type --}}
                <div class="mb-3">
                    <label>Animal Type:</label>
                    <select class="form-select" name="animal_type" required>
                        @foreach($animalTypes as $type)
                            <option value="{{ $type }}" 
                                {{ (isset($clinicService) && $clinicService->animal_type == $type) ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('clinic-services.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for dynamic service filtering --}}
<script>
document.getElementById('clinicSelect').addEventListener('change', function() {
    let clinicId = this.value;
    fetch(`/clinics/${clinicId}/services`)
        .then(response => response.json())
        .then(data => {
            let serviceSelect = document.getElementById('serviceSelect');
            serviceSelect.innerHTML = '';
            data.forEach(service => {
                let option = document.createElement('option');
                option.value = service.id;
                option.text = service.service_name;
                serviceSelect.add(option);
            });
        });
});
</script>
@endsection
