@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2 class="mb-3">Emergency Contacts</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Pet Owner</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Relationship</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emergency_contacts as $contact)
                <tr>
                    <td>{{ $contact->petOwner->full_name }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->relationship }}</td>
                    <td>
                        <a href="{{ route('emergency-contacts.show', $contact->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('emergency-contacts.edit', $contact->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ url('/') }}" class="btn btn-primary">Home</a>

    </div>
</div>
@endsection
