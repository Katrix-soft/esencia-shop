<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katrix_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->references('id')->on('katrix_orders')
                  ->onDelete('cascade');

            // ID del producto/variante del host (no FK — flexible)
            $table->unsignedBigInteger('product_id');

            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->json('features')->nullable(); // Atributos (talle, color, etc.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katrix_order_items');
    }
};
