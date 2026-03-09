@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Clinics List</h1>
    <a href="{{ route('clinics.create') }}" class="btn btn-primary">Add New Clinic</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clinics as $clinic)
        <tr>
            <td>{{ $clinic->clinic_name }}</td>
            <td>{{ $clinic->address }}</td>
            <td>{{ $clinic->phone }}</td>
            <td>
                <a href="{{ route('clinics.show', $clinic->id) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('clinics.edit', $clinic->id) }}" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ url('/') }}" class="btn btn-primary">Home</a>

@endsection
