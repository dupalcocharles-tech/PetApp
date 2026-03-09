@extends('layouts.app')
@section('content')
<h1>Edit Clinic</h1>

<form method="POST" action="{{ route('clinics.update', $clinic->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Clinic Name:</label>
        <input type="text" name="clinic_name" class="form-control" value="{{ $clinic->clinic_name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Address:</label>
        <input type="text" name="address" class="form-control" value="{{ $clinic->address }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone:</label>
        <input type="text" name="phone" class="form-control" value="{{ $clinic->phone }}" required>
    </div>

    {{-- Select Animals --}}
    <div class="mb-3">
        <label class="form-label">Animals Specialized In:</label>
        <select name="animals[]" class="form-control" multiple required>
            @foreach($animals as $animal)
                <option value="{{ $animal->id }}" 
                    @if(in_array($animal->id, $clinicAnimals)) selected @endif>
                    {{ $animal->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Select Services --}}
    <div class="mb-3">
        <label class="form-label">Services Offered:</label>
        <select name="services[]" class="form-control" multiple required>
            @foreach($services as $service)
                <option value="{{ $service->id }}" 
                    @if(in_array($service->id, $clinicServices)) selected @endif>
                    {{ $service->service_name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
