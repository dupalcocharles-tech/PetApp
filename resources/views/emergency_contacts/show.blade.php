@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Emergency Contact Details</h2>
    <p><strong>Pet Owner:</strong> {{ $emergency_contact->petOwner->full_name }}</p>
    <p><strong>Name:</strong> {{ $emergency_contact->name }}</p>
    <p><strong>Phone:</strong> {{ $emergency_contact->phone }}</p>
    <p><strong>Relationship:</strong> {{ $emergency_contact->relationship }}</p>
    <a href="{{ route('emergency-contacts.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
