<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katrix_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');                    // Hogar, Trabajo, Otro
            $table->string('description')->nullable(); // Ej: Casa de mamá
            $table->string('province');
            $table->string('locality');
            $table->string('zip_code');
            $table->string('district')->nullable();    // Barrio
            $table->string('address');                 // Calle y número
            $table->string('apartment')->nullable();   // Piso/Depto
            $table->string('reference')->nullable();   // Referencias
            $table->string('contact');                 // Nombre del receptor
            $table->string('phone');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katrix_addresses');
    }
};
