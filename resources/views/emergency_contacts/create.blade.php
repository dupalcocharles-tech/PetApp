@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h2>Add Emergency Contact</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('emergency_contacts.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Pet Owner:</label>
                    <select class="form-select" name="pet_owner_id">
                        @foreach($pet_owners as $owner)
                            <option value="{{ $owner->id }}">{{ $owner->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Relationship:</label>
                    <input type="text" class="form-control" name="relationship">
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('emergency-contacts.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
