@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Unverified Clinics</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Clinic Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clinics as $clinic)
                <tr>
                    <td>{{ $clinic->id }}</td>
                    <td>{{ $clinic->clinic_name }}</td>
                    <td>{{ $clinic->email }}</td>
                    <td>
                        <form action="{{ route('admin.clinics.verify', $clinic->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Verify</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No unverified clinics.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
