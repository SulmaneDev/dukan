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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->json('imeis');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->decimal('percent_discount', 10, 2)->default(0.00);
            $table->decimal('fixed_discount', 10, 2)->default(0.00);
            $table->decimal('coupon_discount', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->nullableMorphs('party');
            $table->decimal('order_tax', 10, 2)->default(0);
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
