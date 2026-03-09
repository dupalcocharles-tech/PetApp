@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2>Pet Details</h2>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $pet->name }}</p>
            <p><strong>Species:</strong> {{ $pet->species }}</p>
            <p><strong>Breed:</strong> {{ $pet->breed }}</p>
            <p><strong>Age:</strong> {{ $pet->age }}</p>
            <p><strong>Gender:</strong> {{ $pet->gender }}</p>
            <a href="{{ route('pets.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
@endsection
