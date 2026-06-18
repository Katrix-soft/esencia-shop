<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('katrix_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Snapshot de dirección (no FK — para mantener histórico)
            $table->json('shipping_address');

            $table->string('payment_method');                           // mercadopago, bank_transfer, cash
            $table->string('payment_status')->default('pending');       // pending, paid, failed, refunded
            $table->string('mp_payment_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->string('status')->default('pending');               // pending, processing, shipped, delivered, cancelled

            $table->decimal('shipping_cost', 10, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);

            // Datos de transferencia bancaria
            $table->string('transfer_issuer_name')->nullable();
            $table->string('transfer_issuer_cuit')->nullable();
            $table->string('transfer_receipt_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('katrix_orders');
    }
};
