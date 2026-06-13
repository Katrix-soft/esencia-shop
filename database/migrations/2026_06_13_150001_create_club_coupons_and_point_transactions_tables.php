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
        Schema::create('club_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('points_required');
            $table->decimal('discount_amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // can be positive or negative
            $table->enum('type', ['earned', 'redeemed', 'adjustment'])->default('earned');
            $table->string('description');
            $table->nullableMorphs('reference'); // reference_type and reference_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('club_coupons');
    }
};
