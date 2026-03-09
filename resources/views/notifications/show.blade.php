@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Notification</h1>

    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Message:</strong> {{ $notification->message }}</p>
            <p class="card-text"><strong>Read:</strong> {{ $notification->is_read ? 'Yes' : 'No' }}</p>
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
