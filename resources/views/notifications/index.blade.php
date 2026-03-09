@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notifications</h1>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Message</th>
                <th>Read</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{ $notification->message }}</td>
                <td>{{ $notification->is_read ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ url('/') }}" class="btn btn-primary">Home</a>

</div>
@endsection
