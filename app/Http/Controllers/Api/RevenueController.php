<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function getRevenue(Request $request)
    {
        $type = $request->query('type', 'day'); // Mặc định lấy theo ngày

        $query = DB::table('orders')
            ->selectRaw("SUM(total_amount) as revenue, DATE(created_at) as date")
            ->where('status_id', 5); // Chỉ lấy đơn hàng đã hoàn thành

        switch ($type) {
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                      ->groupBy(DB::raw('WEEK(created_at)'));
                break;
            case 'month':
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                      ->groupBy(DB::raw('MONTH(created_at)'));
                break;
            default:
                $query->whereDate('created_at', Carbon::today())
                      ->groupBy('date');
        }

        $data = $query->orderBy('date')->get();

        return response()->json($data);
    }
}
