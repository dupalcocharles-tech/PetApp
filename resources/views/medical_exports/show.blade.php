@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Medical Export Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Pet: {{ $medical_export->pet->name }}</h5>
            <p class="card-text"><strong>Export Data:</strong></p>
            <p class="card-text">{{ $medical_export->export_data }}</p>
            <a href="{{ route('medical-exports.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
