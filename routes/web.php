<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalExportController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PetOwnerController;
use App\Http\Controllers\ClinicServiceController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ClinicReportController;
use App\Http\Controllers\AdminReportController;

// --------------------
// Homepage Redirect
// --------------------
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::guard('clinic')->check()) {
        return redirect()->route('clinic.dashboard');
    } elseif (Auth::guard('pet_owner')->check()) {
        return redirect()->route('pet_owner.dashboard');
    }
    return view('auth.choose');
})->name('home');

// --------------------
// Default Login Redirect
// --------------------
Route::get('/login', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::guard('clinic')->check()) {
        return redirect()->route('clinic.dashboard');
    } elseif (Auth::guard('pet_owner')->check()) {
        return redirect()->route('pet_owner.dashboard');
    }
    return view('auth.choose');
})->name('login');

// --------------------
// Admin Login (Password-Only)
// --------------------
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// --------------------
// Admin Routes (Protected)
// --------------------
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.add');
    Route::get('/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');

    Route::post('/clinics/{id}/verify', [AdminController::class, 'verifyClinic'])->name('admin.clinics.verify');
    Route::post('/clinics/{id}/approve-subscription', [AdminController::class, 'approveSubscription'])->name('admin.clinics.approveSubscription');
    Route::post('/clinics/{id}/test-expiry', [AdminController::class, 'testSubscriptionExpiry'])->name('admin.clinics.testExpiry');
    Route::delete('/clinics/{id}/delete', [AdminController::class, 'deleteClinic'])->name('admin.clinics.delete');
    Route::get('/clinics/{id}/details', [AdminController::class, 'getClinicDetails'])->name('admin.clinics.details');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/reports/{id}/review', [AdminReportController::class, 'markReviewed'])->name('admin.reports.review');
    Route::post('/reports/{id}/dismiss', [AdminReportController::class, 'dismiss'])->name('admin.reports.dismiss');

    Route::post('/clinics/{id}/ban', [AdminController::class, 'banClinic'])->name('admin.clinics.ban');
    Route::post('/clinics/{id}/unban', [AdminController::class, 'unbanClinic'])->name('admin.clinics.unban');
    Route::get('/appeals', [AdminController::class, 'viewAppeals'])->name('admin.appeals.index');
    Route::post('/appeals/{id}/approve', [AdminController::class, 'approveAppeal'])->name('admin.appeals.approve');
    Route::post('/appeals/{id}/reject', [AdminController::class, 'rejectAppeal'])->name('admin.appeals.reject');
});

// --------------------
// Authentication Routes (Clinic & Pet Owner)
// --------------------
Route::prefix('auth')->group(function () {
    Route::get('login/{role}', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('login/{role}', [AuthController::class, 'login'])->name('auth.login.post');
    Route::get('signup/{role}', [AuthController::class, 'showSignupForm'])->name('auth.signup');
    Route::post('signup/{role}', [AuthController::class, 'signup'])->name('auth.signup.post');
});

// --------------------
// Logout
// --------------------
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --------------------
// Pet Owner Routes
// --------------------
Route::middleware(['auth:pet_owner'])->group(function () {

    // Dashboard
    Route::get('/pet-owner/dashboard', [PetOwnerController::class, 'dashboard'])->name('pet_owner.dashboard');

    // Profile management
    Route::get('/pet-owner/profile/edit', [PetOwnerController::class, 'edit'])->name('pet_owner.edit');
    Route::put('/pet-owner/profile/update', [PetOwnerController::class, 'update'])->name('pet_owner.update');

    // Booking
    Route::post('/pet-owner/book-service', [PetOwnerController::class, 'bookAppointment'])->name('pet_owner.bookService');

    // Pet resource routes
    Route::resource('pets', PetController::class);

    // Animal clinics
    Route::get('/pet-owner/animal/{animal}/clinics', [PetOwnerController::class, 'clinicsByAnimal'])
        ->name('pet_owner.animal.clinics');

    // Payment Routes
    Route::post('/payment/upload-receipt/{appointment}', [App\Http\Controllers\PaymentController::class, 'uploadReceipt'])->name('payment.uploadReceipt');
    Route::get('/payment/checkout/{appointment}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // ✅ Add review submission here
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Cancel Appointment
    Route::put('/appointments/{id}/cancel', [PetOwnerController::class, 'cancelAppointment'])->name('appointments.cancel');

    Route::post('/clinic-reports', [ClinicReportController::class, 'store'])->name('clinicReports.store');
});


// --------------------
// Clinic Routes
// --------------------
Route::middleware(['auth:clinic', \App\Http\Middleware\CheckClinicBan::class])->group(function () {
// --------------------
// Clinic Staff Routes (USES SAME clinic GUARD)
// --------------------
Route::get('/staff/dashboard', [App\Http\Controllers\ClinicStaffController::class, 'dashboard'])
    ->name('staff.dashboard');

Route::get('/staff/edit', [App\Http\Controllers\ClinicStaffController::class, 'edit'])
    ->name('staff.edit');

Route::put('/staff/update', [App\Http\Controllers\ClinicStaffController::class, 'update'])
    ->name('staff.update');

Route::put('/staff/availability', [App\Http\Controllers\ClinicStaffController::class, 'updateAvailability'])
    ->name('staff.updateAvailability');

    // Waiting / gating page for clinics
    Route::get('/clinic/waiting', function () {
        $clinic = Auth::guard('clinic')->user();

        if (!$clinic->is_subscribed) {
            return redirect()->route('clinic.subscription');
        }

        if ($clinic->is_verified) {
            return redirect()->route('clinic.dashboard');
        }

        return view('clinic.waiting');
    })->name('clinic.waiting');

    Route::get('/clinic/subscription', [ClinicController::class, 'subscription'])->name('clinic.subscription');
    Route::post('/clinic/subscription', [ClinicController::class, 'submitSubscription'])->name('clinic.subscription.submit');

    // Clinic Dashboard (only verified)
    Route::get('/clinic/dashboard', [AppointmentController::class, 'index'])->name('clinic.dashboard');
    
    // ✅ Get Pet Owner History
    Route::get('/clinic/pet-owner-history/{id}', [AppointmentController::class, 'getPetOwnerHistory'])
        ->name('clinic.petOwnerHistory');

    // Clinic Records
    Route::get('/clinic/records', [ClinicController::class, 'records'])->name('clinic.records');

    // Clinic profile management
    Route::get('/clinic/profile', [ClinicController::class, 'profile'])->name('clinic.profile');
    Route::get('/clinic/reviews', [ClinicController::class, 'reviews'])->name('clinic.reviews');
    Route::get('/clinic/edit', [ClinicController::class, 'edit'])->name('clinic.edit');
    Route::put('/clinic/update', [ClinicController::class, 'update'])->name('clinic.update');

    // Clinic animals
    Route::get('/clinic/animals', [ClinicController::class, 'animals'])->name('clinic.animals.index');

    // Clinic services
    Route::resource('services', ServiceController::class);
    Route::post('/services/{service}/toggle-availability', [ServiceController::class, 'toggleAvailability'])
        ->name('services.toggleAvailability');
    Route::post('/services/{service}/home-slots', [ServiceController::class, 'addHomeSlot'])
        ->name('services.homeSlots.add');
    Route::delete('/services/{service}/home-slots', [ServiceController::class, 'deleteHomeSlot'])
        ->name('services.homeSlots.delete');
    Route::post('/services/{service}/home-slots/clear', [ServiceController::class, 'clearHomeSlots'])
        ->name('services.homeSlots.clear');
    Route::get('/clinic/{clinic}/services/manage', [ClinicServiceController::class, 'index'])
        ->name('clinics.services.manage');
    Route::post('/clinic/{clinic}/services', [ClinicServiceController::class, 'store'])
        ->name('clinics.services.store');
    Route::get('/clinics/{clinic}/services', [ClinicServiceController::class, 'getServicesByClinic'])
        ->name('clinics.services');

    // Clinic animal specialization
    Route::post('/clinic/animals/update', [ClinicController::class, 'updateAnimals'])->name('clinic.animals.update');
    Route::get('/clinics/by-animal', [ClinicController::class, 'clinicsByAnimal'])->name('clinics.byAnimal');

    // Complete Appointment (added here inside clinic middleware)
    Route::post('/clinic/complete-appointment', [AppointmentController::class, 'complete'])
        ->name('clinic.completeAppointment');

    Route::get('/clinic/banned', [ClinicController::class, 'banned'])->name('clinic.banned');
    Route::post('/clinic/ban-appeals', [ClinicController::class, 'submitBanAppeal'])->name('clinic.banAppeal.store');
});

// --------------------
// Appointment Routes
// --------------------

// 🟢 Pet Owner - Create Appointment
Route::middleware(['auth:pet_owner'])->group(function () {
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// 🟦 Clinic - Manage Appointments
Route::middleware(['auth:clinic', \App\Http\Middleware\CheckClinicBan::class])->group(function () {
    Route::get('/clinic/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

    // ✅ Support both PUT and PATCH for updates
    Route::match(['put', 'patch'], '/appointments/{id}', [AppointmentController::class, 'update'])
        ->name('appointments.update');

    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');

    // ✅ Accept Appointment (changes status to pending)
    Route::post('/clinic/appointments/{id}/accept', [AppointmentController::class, 'accept'])
        ->name('clinic.appointments.accept');

        
});

// --------------------
// Animal AJAX Route
// --------------------
Route::get('/animal/{animal}/clinics', [AnimalController::class, 'clinics'])
    ->name('animal.clinics')
    ->middleware(['auth:pet_owner']);

// --------------------
// Completed Appointments for Clinic
// --------------------
Route::get('/clinic/completed-appointments', [AppointmentController::class, 'completedAppointments'])
    ->name('clinic.completedAppointments')
    ->middleware(['auth:clinic', \App\Http\Middleware\CheckClinicBan::class]);

Route::get('/clinic/next-appointments', [AppointmentController::class, 'nextAppointments'])
    ->name('clinic.nextAppointments')
    ->middleware(['auth:clinic', \App\Http\Middleware\CheckClinicBan::class]);

// --------------------
// Remove duplicate /appointments/complete route if unnecessary
// Route::post('/appointments/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
// Reminder for clinic appointments
Route::post('/clinic/send-reminder', [App\Http\Controllers\ClinicController::class, 'sendReminder'])
    ->name('clinic.sendReminder')
    ->middleware(['auth:clinic', \App\Http\Middleware\CheckClinicBan::class]);


    Route::put('/clinic/password', [ClinicController::class, 'updatePassword'])->name('clinic.updatePassword');

// --------------------
// Password Reset Routes
// --------------------
use App\Http\Controllers\ForgotPasswordController;

Route::get('/password/reset/{role}', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/find-account', [ForgotPasswordController::class, 'findAccount'])->name('password.find');
Route::get('/password/confirm/{id}', [ForgotPasswordController::class, 'showConfirmAccount'])->name('password.confirm');
Route::post('/password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::get('/password/verify-otp/{id}', [ForgotPasswordController::class, 'showVerifyOtp'])->name('password.verifyForm');
Route::post('/password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');
Route::get('/password/change/{id}', [ForgotPasswordController::class, 'showChangePassword'])->name('password.changeForm');
Route::post('/password/change', [ForgotPasswordController::class, 'changePassword'])->name('password.change');
