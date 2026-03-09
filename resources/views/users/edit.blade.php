@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        overflow: hidden; /* prevent scrolling */
    }
    .profile-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-start;
        padding: 30px;
        height: 100%;
        box-sizing: border-box;
    }
    .profile-left {
        display: flex;
        flex-direction: row;
        gap: 30px;
        align-items: flex-start;
    }
    .profile-image {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }
    .profile-info {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 400px;
    }
    .profile-info label {
        font-weight: 600;
    }
    .password-section {
        margin-top: 30px;
        max-width: 600px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .buttons-top {
        display: flex;
        gap: 15px;
    }
    .buttons-top button, .buttons-top a {
        min-width: 120px;
    }
    .profile-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
</style>

<div class="profile-container">

    {{-- Left side: Image + Info --}}
    <div class="profile-left">

        {{-- Profile Image --}}
        <div>
            @if($petOwner->profile_image)
                <img src="{{ asset('storage/' . $petOwner->profile_image) }}" 
                     alt="Profile Image" class="profile-image mb-3">
            @else
                <img src="{{ asset('images/default_user.png') }}" 
                     alt="Default Image" class="profile-image mb-3">
            @endif
            <div class="mt-2">
                <label class="form-label">Upload New Image:</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="profile-info">
            <div>
                <label>Full Name:</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $petOwner->full_name) }}">
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $petOwner->email) }}">
            </div>
            <div>
                <label>Phone:</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $petOwner->phone) }}">
            </div>
            <div>
                <label>Address:</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $petOwner->address) }}">
            </div>
        </div>

    </div>

    {{-- Right side: Buttons --}}
    <div class="profile-right">
        <form method="POST" action="{{ route('pet_owner.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="buttons-top mb-3">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>

            {{-- Change Password --}}
            <div class="password-section">
                <div>
                    <label>New Password (optional):</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                </div>
                <div>
                    <label>Confirm New Password:</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Leave blank if not changing password">
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
