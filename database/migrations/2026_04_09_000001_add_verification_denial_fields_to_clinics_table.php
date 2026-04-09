<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->timestamp('verification_denied_at')->nullable();
            $table->text('verification_denied_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('verification_denied_at');
            $table->dropColumn('verification_denied_reason');
        });
    }
};

