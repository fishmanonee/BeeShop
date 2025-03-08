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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('productvariant_id');
            $table->unsignedInteger('quantity');
            //$table->decimal('price', 10, 2);
            //$table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            //$table->string('product_name', 255);
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('productvariant_id')->references('id')->on('product_variants')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
