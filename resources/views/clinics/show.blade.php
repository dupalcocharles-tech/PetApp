@extends('layouts.app')
@section('content')
<h1>Clinic Details</h1>

<div class="card p-3 mb-3">
    <p><strong>Name:</strong> {{ $clinic->clinic_name }}</p>
    <p><strong>Address:</strong> {{ $clinic->address }}</p>
    <p><strong>Phone:</strong> {{ $clinic->phone }}</p>
</div>

<a href="{{ route('clinics.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
