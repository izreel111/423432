<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Владелец заказа (если нужен)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Поля для логики
            $table->string('status')->default('new'); // new, in_progress, delivered, etc.
            $table->decimal('total_cost', 8, 2)->default(0);
            $table->decimal('distance_cost', 8, 2)->default(0);
            $table->boolean('insurance')->default(false);

            // Контактные поля
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();

            // Адреса
            $table->string('pick_up_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('delivery_state')->nullable();
            $table->string('delivery_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};