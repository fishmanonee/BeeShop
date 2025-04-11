<?php

// database/migrations/xxxx_xx_xx_create_visitors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->date('visited_at'); // ngày truy cập (không có time)
            $table->integer('visit_count')->default(1);
            $table->timestamps();

            // unique theo IP và ngày
            $table->unique(['ip_address', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
