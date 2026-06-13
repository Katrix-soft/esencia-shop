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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('club_points')->default(0)->after('email_verified_at');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('points_multiplier', 5, 2)->default(1.00)->after('discount');
            $table->boolean('is_club_exclusive')->default(false)->after('points_multiplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('club_points');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['points_multiplier', 'is_club_exclusive']);
        });
    }
};
