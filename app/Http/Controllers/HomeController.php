<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Type;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

use App\Models\OrderDetail;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        ///// Thống kê lượt truy cập /////
        $totalUsers = User::count(); // Tổng user
        $totalVisitors = Visitor::sum('visit_count'); // Tổng lượt truy cập
        $todayVisitors = Visitor::where('visited_at', Carbon::today())->count(); // Lượt truy cập hôm nay
        $currentWeekUsers = User::whereBetween(
            'created_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]
        )->count(); // Lấy số lượng user đăng ký trong tuần này
        ///// Thống kê tổng user /////
        $lastWeekUsers = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count(); // Lấy số lượng user đăng ký trong tuần trước
        if ($lastWeekUsers == 0) {  // Tránh chia cho 0 để không bị lỗi
            $growthPercentage = $currentWeekUsers > 0 ? 100 : 0;
        } else {
            $growthPercentage = (($currentWeekUsers - $lastWeekUsers) / $lastWeekUsers) * 100;
        }
        ///// Thống kê doanh thu theo tuần /////
        $totalEarningsThisWeek = Order::where('status_id', 5)
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('total_amount'); // Tổng doanh thu tuần này (7 ngày gần nhất)
        $totalEarningsLastWeek = Order::where('status_id', 5)
            ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->sum('total_amount'); // Tổng doanh thu tuần trước
        $percentageChange = $totalEarningsLastWeek > 0
            ? (($totalEarningsThisWeek - $totalEarningsLastWeek) / $totalEarningsLastWeek) * 100
            : 0; // Tính phần trăm tăng/giảm so với tuần trước
        ///// Thống kê đơn hàng theo tuần /////
        $totalOrders = Order::where('status_id', 5)->count();
        $completedOrders = Order::where('status_id', 5)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count(); // Lấy số đơn hàng có status_id = 5 trong tuần hiện tại
        $lastWeekOrders = Order::where('status_id', 5)
            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->count(); // Lấy số đơn hàng có status_id = 5 trong tuần trước
        $changePercentage = $lastWeekOrders > 0
            ? (($completedOrders - $lastWeekOrders) / $lastWeekOrders) * 100
            : ($completedOrders > 0 ? 100 : 0); // Tính phần trăm thay đổi
        ///// Thống kê tổng sản phẩm /////
        $totalProducts = ProductVariant::sum('stock_quantity');
        $totalCategory = Category::count();
        $totalType = Type::count();
        $products = Product::select(
            'products.id',
            'products.name',
            'products.img',
            DB::raw('COUNT(DISTINCT reviews.id) as five_star_count'),
            DB::raw('COALESCE(total_purchased_sub.total_quantity, 0) as total_purchased'),
            DB::raw('COUNT(DISTINCT wishlist.id) as wishlist_count')
        )
            // Subquery để tính tổng số lượng mua của sản phẩm từ các đơn hàng hoàn thành
            ->leftJoin(DB::raw('(
            SELECT
                pv.product_id,
                SUM(od.quantity) as total_quantity
            FROM product_variants pv
            JOIN order_details od ON pv.id = od.productvariant_id
            JOIN orders o ON od.order_id = o.id
            WHERE o.status_id = 5
            GROUP BY pv.product_id
        ) as total_purchased_sub'), 'products.id', '=', 'total_purchased_sub.product_id')
            // Join với bảng reviews để đếm số đánh giá 5 sao
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.rating', 5); // Chỉ đếm đánh giá 5 sao
            })
            // Join với wishlist để đếm số lượt yêu thích
            ->leftJoin('wishlist', 'products.id', '=', 'wishlist.product_id')
            // Nhóm theo product_id để tính tổng số lượt mua và các giá trị khác
            ->groupBy('products.id', 'products.name', 'products.img', 'total_purchased_sub.total_quantity')
            // Sắp xếp theo số đánh giá 5 sao, số lượt mua, và số lượt yêu thích
            ->orderByDesc('five_star_count')
            ->orderByDesc('total_purchased')
            ->orderByDesc('wishlist_count')
            // Giới hạn kết quả là 5 sản phẩm
            ->limit(5)
            ->get();
        // Sắp xếp sau khi lấy dữ liệu
        $products = $products->sortByDesc('five_star_count')   // Sắp xếp theo số lượng đánh giá 5 sao
            ->sortByDesc('wishlist_count')    // Sắp xếp theo số lượt yêu thích
            ->sortByDesc('total_purchased');  // Sắp xếp theo tổng lượt mua
        // Giới hạn số lượng sản phẩm
        $products = $products->take(10);
        return view('admin.index', compact(
            'totalUsers','totalOrders',
            'growthPercentage','totalVisitors',
            'percentageChange','todayVisitors',
            'totalEarningsThisWeek','completedOrders',
            'changePercentage','totalProducts',
            'totalCategory','totalType','products'
        ));
    }


    public function getReviewsSummary()
    {
        $reviewCounts = Review::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        // Đảm bảo đủ các mức rating từ 1 đến 5 sao, nếu không có thì mặc định là 0
        $ratings = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratings[$i] = $reviewCounts[$i] ?? 0;
        }

        return response()->json($ratings);
    }

    public function getEarningsLast7Days()
    {
        $earningsLast7Days = Order::where('status_id', 5)
            ->whereDate('created_at', '>=', Carbon::now()->subDays(6)->toDateString()) // 7 ngày gần nhất
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('created_at', 'asc')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->pluck('total', 'date');

        $dates = collect(range(0, 6))->map(function ($day) {
            return Carbon::now()->subDays($day)->toDateString();
        })->reverse()->values();

        $earningsData = $dates->mapWithKeys(function ($date) use ($earningsLast7Days) {
            return [$date => $earningsLast7Days[$date] ?? 0];
        });

        return response()->json([
            'labels' => $earningsData->keys(),
            'data' => $earningsData->values(),
        ]);
    }

    public function getTopRatedProducts()
    {
        $products = Product::select('products.id', 'products.name', 'products.img')
            ->join('reviews', 'products.id', '=', 'reviews.product_id')
            ->where('reviews.rating', 5)
            ->groupBy('products.id', 'products.name', 'products.img')
            ->selectRaw('COUNT(reviews.id) as five_star_count')
            ->orderByDesc('five_star_count')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function getProductsTotalPurchase()
    {
        $total_purchase = Product::with(['category', 'type', 'product_variants'])
            ->select('products.*')
            ->selectRaw('(SELECT COUNT(*) 
                      FROM order_details 
                      JOIN orders ON order_details.order_id = orders.id 
                      WHERE order_details.productvariant_id IN (
                          SELECT id FROM product_variants WHERE product_variants.product_id = products.id
                      ) 
                      AND orders.status_id = 5) as purchase_count')
            ->get();

        return view('admin.index', compact('total_purchase'));
    }
}
