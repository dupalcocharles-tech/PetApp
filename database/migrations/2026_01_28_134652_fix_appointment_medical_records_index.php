<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointment_medical_records', function (Blueprint $table) {
            // 1. Drop the foreign key
            $table->dropForeign('fk_med_appointment');

            // 2. Drop the incorrect unique constraint on appointment_id
            $table->dropUnique('appointment_id');
            
            // 3. Add the correct composite unique constraint
            // This will also serve as the index for the foreign key
            $table->unique(['appointment_id', 'pet_id'], 'appointment_pet_unique');

            // 4. Re-add the foreign key
            $table->foreign('appointment_id', 'fk_med_appointment')
                  ->references('id')->on('appointments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_medical_records', function (Blueprint $table) {
            $table->dropForeign('fk_med_appointment');
            $table->dropUnique('appointment_pet_unique');

            // Restore the original state
            $table->unique('appointment_id');
            $table->foreign('appointment_id', 'fk_med_appointment')
                  ->references('id')->on('appointments')
                  ->onDelete('cascade');
        });
    }
};
