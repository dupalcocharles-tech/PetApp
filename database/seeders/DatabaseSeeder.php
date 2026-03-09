<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------
        // USERS
        // -------------------------
        DB::table('users')->updateOrInsert(
            ['username' => 'adminuser'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'staffuser'],
            [
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'clinic_staff',
            ]
        );

        DB::table('users')->updateOrInsert(
            ['username' => 'owneruser'],
            [
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'user_type' => 'pet_owner',
            ]
        );

        // -------------------------
        // ADMINS
        // -------------------------
        DB::table('admins')->updateOrInsert(
            ['username' => 'adminuser'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // -------------------------
        // PET OWNERS
        // -------------------------
        DB::table('pet_owners')->updateOrInsert(
            ['username' => 'owneruser'],
            [
                'username' => 'owneruser',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'full_name' => 'Juan Dela Cruz',
                'phone' => '09171234567',
                'address' => 'Manila, Philippines',
            ]
        );

        $petOwnerId = DB::table('pet_owners')->where('username', 'owneruser')->value('id');

        // -------------------------
        // PETS
        // -------------------------
        DB::table('pets')->updateOrInsert(
            ['name' => 'Buddy', 'pet_owner_id' => $petOwnerId],
            [
                'species' => 'Dog',
                'breed' => 'Aspen',
                'age' => 3,
                'gender' => 'Male',
            ]
        );

        $petId = DB::table('pets')->where('name', 'Buddy')->value('id');

        // -------------------------
        // CLINICS
        // -------------------------
        DB::table('clinics')->updateOrInsert(
            ['clinic_name' => 'Happy Paws Veterinary Clinic'],
            [
                'username' => 'happypaws', // required
                'password' => Hash::make('password'), // required
                'email' => 'happypaws@example.com', // required
                'address' => 'Quezon City, Philippines',
                'phone' => '0287654321',
            ]
        );

        $clinicId = DB::table('clinics')
            ->where('clinic_name', 'Happy Paws Veterinary Clinic')
            ->value('id');

        // -------------------------
        // SERVICES
        // -------------------------
        DB::table('services')->updateOrInsert(
            ['service_name' => 'General Check-up'],
            [
                'description' => 'Basic health check-up for pets.',
                'price' => 500.00,
            ]
        );

        $serviceId = DB::table('services')->where('service_name', 'General Check-up')->value('id');

        // -------------------------
        // ANIMALS (ensure at least one exists)
        // -------------------------
        DB::table('animals')->updateOrInsert(
            ['name' => 'Dog'],
            ['description' => 'Domestic Dog']
        );

        $animalId = DB::table('animals')->where('name', 'Dog')->value('id');

        // -------------------------
        // CLINIC SERVICES
        // -------------------------
        DB::table('clinic_services')->updateOrInsert(
            [
                'clinic_id' => $clinicId,
                'service_id' => $serviceId,
                'animal_id' => $animalId,
            ],
            ['price' => 500.00]
        );

        $clinicServiceId = DB::table('clinic_services')
            ->where('clinic_id', $clinicId)
            ->where('service_id', $serviceId)
            ->value('id');

        // -------------------------
        // APPOINTMENTS
        // -------------------------
        DB::table('appointments')->updateOrInsert(
            ['pet_id' => $petId, 'clinic_service_id' => $clinicServiceId],
            [
                'appointment_date' => now()->addDays(2),
                'status' => 'pending',
            ]
        );

        $appointmentId = DB::table('appointments')
            ->where('pet_id', $petId)
            ->where('clinic_service_id', $clinicServiceId)
            ->value('id');

        // -------------------------
        // NOTIFICATIONS
        // -------------------------
        DB::table('notifications')->updateOrInsert(
            ['user_id' => $petOwnerId, 'message' => 'Your appointment has been booked.'],
            ['is_read' => false]
        );

        // -------------------------
        // VISIT NOTES
        // -------------------------
        DB::table('visit_notes')->updateOrInsert(
            ['appointment_id' => $appointmentId],
            ['notes' => 'Pet is healthy but needs vaccination.']
        );

        // -------------------------
        // EMERGENCY CONTACTS
        // -------------------------
        DB::table('emergency_contacts')->updateOrInsert(
            ['pet_owner_id' => $petOwnerId, 'name' => 'Maria Dela Cruz'],
            [
                'phone' => '09998887777',
                'relationship' => 'Sister',
            ]
        );

        // -------------------------
        // QUESTIONNAIRES
        // -------------------------
        DB::table('questionnaires')->updateOrInsert(
            ['pet_id' => $petId],
            [
                'questions' => 'Has your pet been vaccinated?',
                'answers' => 'Yes, last month.',
            ]
        );

        // -------------------------
        // MEDICAL EXPORTS
        // -------------------------
        DB::table('medical_exports')->updateOrInsert(
            ['pet_id' => $petId],
            [
                'export_data' => 'Vaccination records exported on ' . now(),
            ]
        );
    }
}
