@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Pet Owners List</h1>

    <a href="{{ route('pet-owners.create') }}" class="btn btn-primary mb-3">Add New Pet Owner</a>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pet_owners as $owner)
            <tr>
                <td>{{ $owner->full_name }}</td>
                <td>{{ $owner->phone }}</td>
                <td>{{ $owner->address }}</td>
                <td>
                    <a href="{{ route('pet-owners.show', $owner->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('pet-owners.edit', $owner->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pet-owners.destroy', $owner->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ url('/') }}" class="btn btn-primary">Home</a>

</div>
@endsection
