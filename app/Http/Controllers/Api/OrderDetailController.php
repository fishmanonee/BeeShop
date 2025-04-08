<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;


class OrderDetailController extends Controller
{
    public function show($id)
    {
        $order = Order::with(['user', 'orderDetails.productVariant.product'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        return response()->json([
            'order_id' => $order->id,
            'user' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'products' => $order->orderDetails->map(function ($detail) {
                return [
                    'product_name' => $detail->productVariant->product->name,
                    'product_img' => $detail->productVariant->product->img,
                    'variant' => $detail->productVariant->size,
                    'quantity' => $detail->quantity,
                    'price' => $detail->productVariant->price,
                    'total_price' => $detail->total_price,
                ];
            }),
            'total_amount' => $order->total_amount,
        ]);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'productvariant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productVariant = ProductVariant::find($request->productvariant_id);

        if (!$productVariant) {
            return response()->json(['message' => 'Biến thể sản phẩm không tồn tại'], 404);
        }

        $totalPrice = $productVariant->price * $request->quantity;

        $orderDetail = OrderDetail::create([
            'order_id' => $id,
            'productvariant_id' => $request->productvariant_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        

        return response()->json([
            'message' => 'Thêm sản phẩm vào đơn hàng thành công!',
            'data' => $orderDetail
        ], 201);
    }
}
