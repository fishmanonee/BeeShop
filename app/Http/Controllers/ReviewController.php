<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function getReviews($productId)
{
    $reviews = Review::where('product_id', $productId)
        ->with('user:id,name') // Lấy thông tin user đánh giá
        ->latest()
        ->get();

    if ($reviews->isEmpty()) {
        return "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
    }

    // Trả về HTML để hiển thị trên trang
    $html = '<ul class="list-group">';
    foreach ($reviews as $review) {
        $html .= '<li class="list-group-item">';
        $html .= '<strong>' . $review->user->name . '</strong>: ';
        $html .= '<span class="text-warning">' . str_repeat('★', $review->rating) . '</span>';
        $html .= '<p>' . e($review->comment) . '</p>';
        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

}
