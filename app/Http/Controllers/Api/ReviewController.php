<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        // Tạo đánh giá mới
        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id, // Lấy ID người dùng đang đăng nhập
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Đánh giá thành công!',
            'review' => $review
        ], 201);
    }
    public function getReviewsByProduct($productId)
    {
        // Lấy danh sách đánh giá theo product_id
        $reviews = Review::where('product_id', $productId)
            ->with('user:id,name') // Lấy thông tin user (chỉ lấy id và name)
            ->orderBy('created_at', 'desc') // Sắp xếp theo thời gian mới nhất
            ->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }
}
