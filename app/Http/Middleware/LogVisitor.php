<?php

// app/Http/Middleware/LogVisitor.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogVisitor {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $today = now()->toDateString(); // chỉ lấy ngày (yyyy-mm-dd)

        // Nếu URL là admin thì không log
        if ($request->is('admin/*')) {
            return $next($request);
        }

        // Cập nhật hoặc chèn mới lượt truy cập
        DB::table('visitors')->updateOrInsert(
            [
                'ip_address' => $ip,
                'visited_at' => $today,
            ],
            [
                'updated_at' => now(),
                'created_at' => now(),
                'visit_count' => DB::raw('visit_count + 1'),
            ]
        );

        return $next($request);
    }
}
