<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('animal_id');
            $table->decimal('price', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('clinic_id')
                  ->references('id')->on('clinics')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('id')->on('services')
                  ->onDelete('cascade');

            $table->foreign('animal_id')
                  ->references('id')->on('animals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_services');
    }
};
