@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Medical Exports</h1>
    <a href="{{ route('medical-exports.create') }}" class="btn btn-primary mb-3">Add Export</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Pet</th>
                <th>Export Data</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medical_exports as $export)
            <tr>
                <td>{{ $export->pet->name }}</td>
                <td>{{ Str::limit($export->export_data, 50) }}</td>
                <td>
                    <a href="{{ route('medical-exports.edit', $export->id) }}">Edit</a>
                    <a href="{{ route('medical-exports.show', $export->id) }}">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ url('/') }}" class="btn btn-primary">Home</a>

</div>
@endsection
