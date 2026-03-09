@extends('layouts.app')
@section('content')
<h1>Admins List</h1>
<a href="{{ route('admins.create') }}">Add Admin</a>
<table>
<thead><tr><th>User</th><th>Role</th><th>Actions</th></tr></thead>
<tbody>
@foreach($admins as $admin)
<tr>
<td>{{ $admin->user->username }}</td>
<td>{{ $admin->role }}</td>
<td>
<a href="{{ route('admins.show', $admin->id) }}">View</a>
<a href="{{ route('admins.edit', $admin->id) }}">Edit</a>
</td>
</tr>
@endforeach
</tbody>
</table>
<a href="{{ url('/') }}" class="btn btn-primary">Home</a>

@endsection
