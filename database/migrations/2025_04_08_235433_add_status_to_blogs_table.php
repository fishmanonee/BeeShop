<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->boolean('status')->default(0)->after('image'); // 0 = chưa đăng, 1 = đã đăng
        });
    }
    
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
};
