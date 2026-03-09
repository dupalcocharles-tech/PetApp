@extends('layouts.app')
@section('content')
<h1>Add Admin</h1>
<form method="POST" action="{{ route('admins.store') }}">
@csrf
<label>User:</label>
<select name="user_id">
@foreach($users as $user)
<option value="{{ $user->id }}">{{ $user->username }}</option>
@endforeach
</select>
<label>Role:</label>
<input type="text" name="role" value="super_admin">
<button type="submit">Save</button>
</form>
@endsection
