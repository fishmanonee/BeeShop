<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Thêm sản phẩm vào wishlist
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $sessionId = session()->getId();
        $userId = $request->user_id ?? Auth::id(); // Nếu có user_id từ request thì dùng, nếu không thì lấy từ Auth
        $productId = $request->product_id;

        $wishlist = Wishlist::where('product_id', $productId)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->first();

        if ($wishlist) {
            $wishlist->increment('favorite_count'); // Nếu đã có trong wishlist, tăng 1
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $productId,
                'favorite_count' => 1
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích'
        ]);
    }

    /**
     * Lấy danh sách wishlist của người dùng hoặc session hiện tại
     */
    public function getWishlist(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $wishlist = Wishlist::with('product') // Lấy thông tin sản phẩm
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $wishlist
        ]);
    }

    /**
     * Xóa sản phẩm khỏi wishlist
     */
    public function removeFromWishlist($id)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $wishlist = Wishlist::where('id', $id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->first();

        if (!$wishlist) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại trong danh sách yêu thích'
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích'
        ]);
    }

    /**
     * Đồng bộ wishlist khi người dùng đăng nhập
     */
    public function syncWishlist()
    {
        if (!Auth::check()) {
            return;
        }

        $sessionId = session()->getId();
        $userId = Auth::id();

        // Cập nhật user_id cho wishlist của session hiện tại
        Wishlist::where('session_id', $sessionId)
            ->update(['user_id' => $userId, 'session_id' => null]);
    }
}
