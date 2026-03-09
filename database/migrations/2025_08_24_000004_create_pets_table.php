<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::create('pets', function (Blueprint $table) {
        $table->id();
       $table->foreignId('pet_owner_id')->constrained('pet_owners')->onDelete('cascade');

        $table->string('name');
        $table->string('species');
        $table->string('breed')->nullable();
        $table->integer('age')->nullable();
        $table->string('gender')->nullable();
        $table->timestamps();
    });
}


    public function down(): void {
        Schema::dropIfExists('pets');
    }
};
