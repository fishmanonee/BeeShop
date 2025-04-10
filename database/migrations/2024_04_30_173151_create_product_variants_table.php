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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Khóa ngoại kết nối với bảng products
            $table->string('size', 50)->nullable(); // Trường size, kiểu varchar(50), có thể null
            $table->decimal('price', 10, 2); // Trường price, kiểu decimal(10,2)
            $table->decimal('discount_price', 10, 2)->nullable();         

            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            // Định nghĩa khóa ngoại
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
