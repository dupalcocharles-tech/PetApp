@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Clinic Services List</h2>
        <a href="{{ route('clinic-services.create') }}" class="btn btn-primary">Add Clinic Service</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Clinic</th>
                    <th>Service</th>
                    <th>Animal Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clinic_services as $cs)
                <tr>
                    <td>{{ $cs->clinic->clinic_name }}</td>
                    <td>{{ $cs->service->service_name }}</td>
                    <td>{{ $cs->animal_type }}</td>
                    <td>
                        <a href="{{ route('clinic-services.edit', $cs->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('clinic-services.destroy', $cs->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
    </div>
</div>
@endsection
