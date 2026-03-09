@extends('layouts.app')
@section('content')
<h1>Add New Clinic</h1>

<form method="POST" action="{{ route('clinics.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Clinic Name:</label>
        <input type="text" name="clinic_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Address:</label>
        <input type="text" name="address" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone:</label>
        <input type="text" name="phone" class="form-control" required>
    </div>

    {{-- Select Animals --}}
    <div class="mb-3">
        <label class="form-label">Animals Specialized In:</label>
        <select name="animals[]" class="form-control" multiple required>
            @foreach($animals as $animal)
                <option value="{{ $animal->id }}">{{ $animal->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
    </div>

    {{-- Select Services --}}
    <div class="mb-3">
        <label class="form-label">Services Offered:</label>
        <select name="services[]" class="form-control" multiple required>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->service_name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold CTRL (Windows) or CMD (Mac) to select multiple.</small>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
</form>
@endsection
