@extends('layouts.app')
@section('content')
<h1>Edit Admin</h1>
<form method="POST" action="{{ route('admins.update', $admin->id) }}">
@csrf
@method('PUT')
<label>User:</label>
<select name="user_id">
@foreach($users as $user)
<option value="{{ $user->id }}" @if($admin->user_id == $user->id) selected @endif>{{ $user->username }}</option>
@endforeach
</select>
<label>Role:</label>
<input type="text" name="role" value="{{ $admin->role }}">
<button type="submit">Update</button>
</form>
@endsection
