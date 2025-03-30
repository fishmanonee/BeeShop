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
    Route::get('/', [ProductController::class, 'getProducts']);  // Lấy danh sách sản phẩm
    Route::post('/', [ProductController::class, 'store']);       // Thêm sản phẩm mới
    Route::get('/{id}', [ProductController::class, 'getProductById']);  // Lấy sản phẩm theo ID
    Route::put('/{id}', [ProductController::class, 'update']);   // Cập nhật sản phẩm
    Route::delete('/{id}', [ProductController::class, 'delete']); // Xóa sản phẩm
});

// Route nhóm cho Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'getCategories']); // Lấy danh sách danh mục
    Route::post('/', [CategoryController::class, 'store']); // Thêm danh mục mới
    Route::get('/{id}', [CategoryController::class, 'getCategoryById']); // Lấy danh mục theo ID
    Route::put('/{id}', [CategoryController::class, 'update']); // Cập nhật danh mục
    Route::delete('/{id}', [CategoryController::class, 'delete']); // Xóa danh mục
    Route::get('/{id}/products', [CategoryController::class, 'getProductsByCategoryId']); // Lấy sản phẩm theo danh mục
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

    Route::get('/user/{user_id}', [OrderController::class, 'getOrdersByUser']); //



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

//promotions
Route::prefix('promotions')->group(function () {
    Route::get('/', [PromotionController::class, 'index']);
    Route::get('/{id}', [PromotionController::class, 'show']);
});

//order_details
Route::get('/orders/{id}/details', [OrderDetailController::class, 'show']);
Route::post('/orders/{id}/details', [OrderDetailController::class, 'store']);


//wishlist
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']);
