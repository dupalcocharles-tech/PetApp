@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2>Clinic Service Details</h2>
        </div>
        <div class="card-body">
            <p><strong>Clinic:</strong> {{ $clinic_service->clinic->clinic_name }}</p>
            <p><strong>Service:</strong> {{ $clinic_service->service->service_name }}</p>
            <a href="{{ route('clinic-services.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        
    </div>
</div>
@endsection
