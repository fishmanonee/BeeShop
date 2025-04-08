<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RevenueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Đăng ký các route API cho ứng dụng
|
*/

Route::prefix('promotions')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::get('/{id}', [PromotionController::class, 'show']);
});
// Lấy thông tin user (chỉ dành cho user đã đăng nhập qua Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route nhóm cho Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'getProducts']);

    Route::get('/hot', [ProductController::class, 'getHotProducts']);
    Route::get('/{id}', [ProductController::class, 'getProductById']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
Route::get('/products/search/{query}', [ProductController::class, 'search']);
// Route::get('/products/hot', [ProductController::class, 'getHotProducts']);




// Route nhóm cho Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'getCategories']); // Lấy danh sách danh mục
    Route::post('/', [CategoryController::class, 'store']); // Thêm danh mục mới
    Route::get('/{id}', [CategoryController::class, 'getCategoryById']); // Lấy danh mục theo ID
    Route::put('/{id}', [CategoryController::class, 'update']); // Cập nhật danh mục
    Route::delete('/{id}', [CategoryController::class, 'delete']); // Xóa danh mục
    Route::get('/{id}/products', [CategoryController::class, 'getProductsByCategoryId']); // Lấy sản phẩm theo danh mục
});

Route::prefix('types')->group(function () {
    Route::get('/', [TypeController::class, 'getTypes']); // Lấy danh sách loại
    Route::get('/{id}', [TypeController::class, 'getTypeById']); // Lấy danh mục theo ID
    Route::get('/{id}/products', [TypeController::class, 'getProductsByTypeId']); // Lấy sản phẩm theo danh mục
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Lấy danh sách người dùng
    Route::post('/', [UserController::class, 'store']); // Tạo mới người dùng
    Route::get('/{id}', [UserController::class, 'show']); // Lấy thông tin user theo ID
    Route::put('/{id}', [UserController::class, 'update']); // Cập nhật user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Xóa user
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']); // Lấy danh sách đơn hàng
    Route::post('/', [OrderController::class, 'store']); // Tạo đơn hàng mới
    Route::get('/{id}', [OrderController::class, 'show']); // Lấy thông tin đơn hàng theo ID
    Route::put('/{id}', [OrderController::class, 'update']); // Cập nhật đơn hàng
    Route::delete('/{id}', [OrderController::class, 'destroy']); // Xóa đơn hàng
    Route::patch('/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.delete');




    Route::get('/user/{user_id}', [OrderController::class, 'getOrdersByUser']); //
    //trang thống kê
    Route::get('/admin/order-stats', [OrderController::class, 'getOrderStats'])->name('admin.order.stats');
    Route::get('/admin/orders-by-month', [OrderController::class, 'getOrdersByMonth'])->name('admin.orders.byMonth');
    Route::get('/admin/reviews-summary', [HomeController::class, 'getReviewsSummary'])->name('admin.reviewsSummary');
    Route::get('/admin/top-products', [HomeController::class, 'getTopProducts'])->name('admin.topProducts');
    Route::get('/api/earnings-last-7-days', [HomeController::class, 'getEarningsLast7Days'])->name('api.getEarningsLast7Days');
    Route::get('/top-rated-products', [HomeController::class, 'getTopRatedProducts']);
});

Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});



Route::post('/zalopay/payment', [ZaloPayController::class, 'createPayment']);


Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/', [CommentController::class, 'store']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::delete('/{id}', [CommentController::class, 'delete']);
});
Route::get('/products/{id}/comments', [CommentController::class, 'getCommentsByProduct']);


//promotions
Route::prefix('promotions')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::get('/{id}', [PromotionController::class, 'show']);
});

//order_details
Route::get('/orders/{id}/details', [OrderDetailController::class, 'show']);
Route::post('/orders/{id}/details', [OrderDetailController::class, 'store']);



// Wishlist
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']); // Lấy danh sách wishlist
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']); // Thêm sản phẩm vào wishlist
    Route::delete('/wishlist/remove', [WishlistController::class, 'removeFromWishlist']); // Xóa sản phẩm khỏi wishlist
    Route::get('/wishlist/check', [WishlistController::class, 'checkWishlist']); // Kiểm tra sản phẩm có trong wishlist không
    Route::post('/wishlist/sync', [WishlistController::class, 'syncWishlist']); // Đồng bộ wishlist khi đăng nhập
});

Route::post('/zalopay/create', [ZaloPayController::class, 'createOrder']);
Route::post('/zalopay/callback', [ZaloPayController::class, 'callback'])->name('zalopay.callback');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/update-password', [AuthController::class, 'updatePassword']);
});

//wishlist




Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'getReviewsByProduct']);


Route::get('/revenue', [RevenueController::class, 'getRevenue']);
Route::get('/revenue/last7days', [RevenueController::class, 'getRevenueLast7Days']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/update-password', [AuthController::class, 'updatePassword']);
});
