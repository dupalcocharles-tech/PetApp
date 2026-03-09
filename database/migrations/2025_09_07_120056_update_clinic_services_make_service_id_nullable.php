<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_services', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->change();
            }
            if (Schema::hasColumn('clinic_services', 'animal_type')) {
                $table->string('animal_type')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_services', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('clinic_services', 'animal_type')) {
                $table->string('animal_type')->nullable(false)->change();
            }
        });
    }
};
