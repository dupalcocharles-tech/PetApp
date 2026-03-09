@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Add Medical Export</h1>

    <form method="POST" action="{{ route('medical-exports.store') }}">
        @csrf

        <div class="mb-3">
            <label for="pet_id" class="form-label">Pet</label>
            <select name="pet_id" id="pet_id" class="form-select" required>
                <option value="" disabled selected>Select a pet</option>
                @foreach($pets as $pet)
                    <option value="{{ $pet->id }}">{{ $pet->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="export_data" class="form-label">Export Data</label>
            <textarea name="export_data" id="export_data" class="form-control" rows="5" required>{{ old('export_data') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('medical-exports.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
