<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_ban_appeals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_ban_id');
            $table->unsignedBigInteger('clinic_id');
            $table->text('message');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('clinic_ban_id')->references('id')->on('clinic_bans')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->foreign('reviewed_by_admin_id')->references('id')->on('admins')->nullOnDelete();

            $table->index(['clinic_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_ban_appeals');
    }
};

