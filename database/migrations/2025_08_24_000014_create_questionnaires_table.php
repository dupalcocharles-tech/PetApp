<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('questionnaires', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pet_id')->constrained('pets', 'id')->onDelete('cascade');
        $table->text('questions');
        $table->text('answers')->nullable();
        $table->timestamps();
    });
}


    public function down(): void {
        Schema::dropIfExists('questionnaires');
    }
};
