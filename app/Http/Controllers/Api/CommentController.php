<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'product'])->latest()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string'
        ]);

        $comment = Comment::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận đã được thêm!',
            'data' => $comment
        ]);
    }

    public function show($id)
    {
        $comment = Comment::with(['user', 'product'])->find($id);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bình luận'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bình luận'], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bình luận đã được xóa'
        ]);
    }
    public function getCommentsByProduct($productId)
    {
        try {
            $product = Product::with('comments.user')->find($productId);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $product->comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
