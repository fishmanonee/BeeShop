<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class LogVisitor {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $today = now()->startOfDay();
        

        // Nếu URL chứa "/admin", bỏ qua việc ghi nhận lượt truy cập
        if ($request->is('admin/*')) {
            return $next($request);
        }
    
        if (!Visitor::where('ip_address', $ip)->whereDate('visited_at', $today)->exists()) {
            Visitor::create([
                'ip_address' => $ip,
                'visited_at' => now(),
            ]);
        }
        
        return $next($request);
    }
    
}
