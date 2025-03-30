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
            'products.img'
        )
        // Join với bảng reviews để đếm số đánh giá 5 sao
        ->leftJoin('reviews', function($join) {
            $join->on('products.id', '=', 'reviews.product_id')
                 ->where('reviews.rating', 5);
        })
        // Join với bảng product_variants để kết nối với order_details
        ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
        // Join với order_details để lấy số lượt mua
        ->leftJoin('order_details', 'product_variants.id', '=', 'order_details.productvariant_id')
        // Join với orders để chỉ tính những đơn đã hoàn thành
        ->leftJoin('orders', function($join) {
            $join->on('orders.id', '=', 'order_details.order_id')
                 ->where('orders.status_id', 5);
        })
        // Join với wishlist để đếm số lượt yêu thích
        ->leftJoin('wishlist', 'products.id', '=', 'wishlist.product_id')
        ->groupBy('products.id', 'products.name', 'products.img')
        ->selectRaw('COUNT(DISTINCT reviews.id) as five_star_count')
        ->selectRaw('COALESCE(SUM(order_details.quantity), 0) as total_purchased')
        ->selectRaw('COUNT(DISTINCT wishlist.id) as wishlist_count') // Đếm số lượt yêu thích
        ->orderByDesc('five_star_count')
        ->orderByDesc('total_purchased')
        ->orderByDesc('wishlist_count')
        ->limit(10)
        ->get();
    
    



        return view('admin.index', compact(
            'totalUsers',
            'growthPercentage',
            'totalVisitors',
            'percentageChange',
            'todayVisitors',
            'totalEarningsThisWeek',
            'completedOrders',
            'changePercentage',
            'totalProducts',
            'totalCategory',
            'totalType','products'
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

    public function getTopProducts()
    {
        $topProducts = Product::select('products.id', 'products.name', 'products.image')
            ->leftJoin('reviews', function ($join) {
                $join->on('reviews.product_id', '=', 'products.id')
                    ->where('reviews.rating', '=', 5);
            })
            ->leftJoin('order_details', function ($join) {
                $join->on('order_details.product_id', '=', 'products.id')
                    ->join('orders', function ($join) {
                        $join->on('orders.id', '=', 'order_details.order_id')
                            ->where('orders.status_id', '=', '5'); // Chỉ lấy đơn hàng đã hoàn thành
                    });
            })
            ->leftJoin('wishlist', 'wishlist.product_id', '=', 'products.id')
            ->selectRaw('
            products.id, products.name, products.image,
            COUNT(reviews.id) as five_star_reviews,
            COUNT(order_details.id) as purchase_count,
            COUNT(wishlist.id) as favorite_count
        ')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('five_star_reviews')
            ->orderByDesc('purchase_count')
            ->orderByDesc('favorite_count')
            ->limit(10) // Giới hạn top 10 sản phẩm
            ->get();

        return response()->json($topProducts);
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
}
