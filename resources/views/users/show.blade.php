@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Details</h2>

    <div class="card p-3">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>User Type:</strong> {{ ucfirst($user->user_type) }}</p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-primary mt-3">Back</a>
</div>
@endsection
