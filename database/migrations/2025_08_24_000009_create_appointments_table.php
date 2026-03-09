<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Link appointment directly to pet owner
            $table->foreignId('pet_owner_id')
                  ->constrained('pet_owners')
                  ->onDelete('cascade');

            // Link to pet
            $table->foreignId('pet_id')
                  ->constrained('pets')
                  ->onDelete('cascade');

            // Link to clinic service
            $table->foreignId('clinic_service_id')
                  ->constrained('clinic_services')
                  ->onDelete('cascade');

            $table->dateTime('appointment_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
