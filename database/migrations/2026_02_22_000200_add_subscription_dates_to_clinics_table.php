<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->timestamp('subscription_started_at')->nullable()->after('subscription_receipt');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('subscription_started_at');
            $table->dropColumn('subscription_expires_at');
        });
    }
};

