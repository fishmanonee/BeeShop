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
        Schema::table('reviews', function (Blueprint $table) {
            // Nếu bạn muốn thêm ràng buộc hoặc sửa đổi cột
            $table->unsignedBigInteger('product_id')->change();
            $table->unsignedBigInteger('user_id')->change();
            
            // Thêm ràng buộc khóa ngoại nếu chưa có
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Nếu muốn rollback
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
