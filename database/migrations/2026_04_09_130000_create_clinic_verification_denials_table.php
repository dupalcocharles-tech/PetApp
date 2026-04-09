<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_verification_denials', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->nullable();
            $table->string('email')->index();
            $table->text('reason');
            $table->timestamp('denied_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_verification_denials');
    }
};

