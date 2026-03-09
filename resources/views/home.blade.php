<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetApp Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">PetApp</a>
        </div>
    </nav>

    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4">Welcome to PetApp</h1>
            <p class="lead">Manage all your pet clinic operations easily.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col"><a href="{{ route('users.index') }}" class="btn btn-outline-primary w-100">Users</a></div>
            <div class="col"><a href="{{ route('pet-owners.index') }}" class="btn btn-outline-primary w-100">Pet Owners</a></div>
            <div class="col"><a href="{{ route('pets.index') }}" class="btn btn-outline-primary w-100">Pets</a></div>
            <div class="col"><a href="{{ route('clinics.index') }}" class="btn btn-outline-primary w-100">Clinics</a></div>
            <div class="col"><a href="{{ route('services.index') }}" class="btn btn-outline-primary w-100">Services</a></div>
            <div class="col"><a href="{{ route('clinic-services.index') }}" class="btn btn-outline-primary w-100">Clinic Services</a></div>
            <div class="col"><a href="{{ route('appointments.index') }}" class="btn btn-outline-primary w-100">Appointments</a></div>
            <div class="col"><a href="{{ route('notifications.index') }}" class="btn btn-outline-primary w-100">Notifications</a></div>
            <div class="col"><a href="{{ route('visit-notes.index') }}" class="btn btn-outline-primary w-100">Visit Notes</a></div>
            <div class="col"><a href="{{ route('admins.index') }}" class="btn btn-outline-primary w-100">Admins</a></div>
            <div class="col"><a href="{{ route('emergency-contacts.index') }}" class="btn btn-outline-primary w-100">Emergency Contacts</a></div>
            <div class="col"><a href="{{ route('questionnaires.index') }}" class="btn btn-outline-primary w-100">Questionnaires</a></div>
            <div class="col"><a href="{{ route('medical-exports.index') }}" class="btn btn-outline-primary w-100">Medical Exports</a></div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
