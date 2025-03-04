<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Đăng ký các route API cho ứng dụng
|
*/

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
});

Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
Route::post('/payments', [PaymentController::class, 'store']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

