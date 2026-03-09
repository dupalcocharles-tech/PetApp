@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Pet Owner Details</h3>
        </div>
        <div class="card-body">
            <p><strong>Full Name:</strong> {{ $petOwner->full_name }}</p>
            <p><strong>Phone:</strong> {{ $petOwner->phone }}</p>
            <p><strong>Address:</strong> {{ $petOwner->address }}</p>
            <a href="{{ route('pet-owners.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
@endsection
