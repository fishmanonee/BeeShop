<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogVisitor {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $today = now()->startOfDay();
        

        // Nếu URL chứa "/admin", bỏ qua việc ghi nhận lượt truy cập
        if ($request->is('admin/*')) {
            return $next($request);
        }
    
        $visitor = Visitor::firstOrNew(
            ['ip_address' => $ip, 'visited_at' => $today]
        );
        
        $visitor->updated_at = now();
        $visitor->visit_count = ($visitor->exists ? $visitor->visit_count + 1 : 1);
        $visitor->save();
        
        
        
        
        
        return $next($request);
    }
    
}