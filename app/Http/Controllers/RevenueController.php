<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function getRevenueLast7Days()
{
    $data = DB::table('orders')
        ->selectRaw("SUM(total_amount) as revenue, DATE(created_at) as date")
        ->where('status_id', 5)
        ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return response()->json($data);
}


}
