@extends('layouts.app')
@section('content')
<h1>Admin Details</h1>
<p>User: {{ $admin->user->username }}</p>
<p>Role: {{ $admin->role }}</p>
<a href="{{ route('admins.index') }}">Back</a>
@endsection
