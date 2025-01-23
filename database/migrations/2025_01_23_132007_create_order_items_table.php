<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('item_type');     // box, furniture, door, tv
            $table->string('item_subtype')->nullable(); // XL, L, M, 64", etc.
            $table->integer('quantity')->default(1);

            // Для мебели: размеры
            $table->decimal('length', 8,2)->nullable();
            $table->decimal('width', 8,2)->nullable();
            $table->decimal('height', 8,2)->nullable();

            // Итоговая стоимость (за все quantity)
            $table->decimal('cost', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};