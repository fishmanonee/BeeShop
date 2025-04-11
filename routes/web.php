<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;


// Admin controller
use App\Http\Controllers\AdminController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// admin dashboard
Route::get('/admin2/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/', [HomeController::class, 'index'])->name('dashboard.index');
Route::get('/admin2/users', [AdminController::class, 'userlist'])->name('admin.users');
Route::get('/admin2/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/admin2/types', [TypeController::class, 'index'])->name('types.index');
Route::get('/admin2/orders', [OrderController::class, 'index'])->name('orders.index');

Route::get('/admin2/promotions', [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/admin2/comments', [CommentController::class, 'index'])->name('comments.index');

// orders
Route::get('/admin2/orders/pending', [OrderController::class, 'pending'])->name('orders.pending');
Route::get('/admin2/orders/confirmed', [OrderController::class, 'confirmed'])->name('orders.confirmed');
Route::get('/admin2/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/admin2/orders/shipping', [OrderController::class, 'shipping'])->name('orders.shipping');
Route::get('/admin2/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::put('/admin2/orders/{id}/update', [OrderController::class, 'updateOrder'])->name('orders.update');
Route::patch('/admin2/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.delete');



// products
Route::post('admin2/products/store', [ProductController::class, 'store'])->name('product.store');
Route::get('admin2/product/create', [ProductController::class, 'create'])->name('product.create');
Route::delete('/admin2/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
Route::get('/admin2/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.product.edit');
Route::post('/admin2/products/update/{id}', [AdminController::class, 'updateProduct'])->name('admin.product.update');
Route::get('/admin2/products/{id}', [ProductController::class, 'show'])->name('admin.product.show');

// types
Route::get('/admin2/type/create', [TypeController::class, 'create'])->name('type.create');
Route::post('/admin2/type/store', [TypeController::class, 'store'])->name('type.store');


// categories
Route::get('/admin2/category/create', [AdminController::class, 'createCategory'])->name('admin.category.create');
Route::post('/admin2/category/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
Route::delete('/admin2/category/{id}', [AdminController::class, 'destroyCategory'])->name('admin.category.destroy');
Route::get('/admin/category/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.category.edit');
Route::put('/admin/category/{id}', [AdminController::class, 'updateCategory'])->name('admin.category.update');


// promotions
Route::get('/admin2/promotion/create', [PromotionController::class, 'create'])->name('promotion.create');
Route::post('/admin2/promotion/store', [PromotionController::class, 'store'])->name('promotion.store');
Route::delete('/admin2/promotion/{id}', [PromotionController::class, 'delete'])->name('promotion.delete');
Route::get('/admin2/promotion/{id}/edit', [PromotionController::class, 'edit'])->name('promotion.edit');
Route::put('/admin2/promotion/{id}', [PromotionController::class, 'update'])->name('promotion.update');

// comments
Route::delete('admin2/comments/{id}', [CommentController::class, 'delete'])->name('comments.delete');

//reviews
Route::get('admin2/products/{id}/reviews', [ProductController::class, 'getReviews']);

Route::get('/products/{product}/reviews', [ReviewController::class, 'getReviews'])->name('products.reviews');

Route::get('/admin2/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
Route::get('/admin2/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin2/blogs/store', [BlogController::class, 'store'])->name('admin.blogs.store');
Route::get('/admin2/blogs/{id}', [BlogController::class, 'show'])->name('admin.blogs.show');
Route::get('/admin2/blogs/{id}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('/admin2/blogs/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('/admin2/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');