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
        Schema::table('products', function (Blueprint $table) {
            $table->string('fragella_id')->nullable()->unique()->after('id');
            $table->string('brand')->nullable()->after('description');
            $table->integer('year')->nullable()->after('brand');
            $table->decimal('rating', 3, 2)->nullable()->after('year');
            $table->string('popularity')->nullable()->after('rating');
            $table->string('gender')->nullable()->after('popularity');
            $table->string('longevity')->nullable()->after('gender');
            $table->string('sillage')->nullable()->after('longevity');
            
            $table->json('general_notes')->nullable()->after('sillage');
            $table->json('main_accords')->nullable()->after('general_notes');
            $table->json('main_accords_percentage')->nullable()->after('main_accords');
            $table->json('notes')->nullable()->after('main_accords_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'fragella_id',
                'brand',
                'year',
                'rating',
                'popularity',
                'gender',
                'longevity',
                'sillage',
                'general_notes',
                'main_accords',
                'main_accords_percentage',
                'notes',
            ]);
        });
    }
};
